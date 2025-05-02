<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use Illuminate\Support\Facades\DB;
use App\Models\PenjualanDetailModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenjualanController extends Controller
{
    public function index(){

        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list'  => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';


        $users = UserModel::all();

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'users'      => $users,
            'activeMenu' => $activeMenu
        ]);
    }


 
    public function list(Request $request)
    {
        /* ───────────────────────────────
           1. Query dasar + relasi user
        ─────────────────────────────── */
        $penjualans = PenjualanModel::with('user')   // eager‑load kasir
            ->select('penjualan_id','user_id','pembeli',
                     'penjualan_kode','penjualan_tanggal');
    
        /* ───────────────────────────────
           2. Filter berdasarkan user
        ─────────────────────────────── */
        if ($request->filled('user_id')) {
            $penjualans->where('user_id', $request->user_id);
        }
    
        /* ───────────────────────────────
           3. Filter periode tanggal
           ─  Input datang dari <input type="date">
           ─  Tambahkan waktu 00:00 & 23:59
        ─────────────────────────────── */
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            // validasi minimal: pastikan tgl_akhir >= tgl_awal
            if ($request->tgl_akhir < $request->tgl_awal) {
                // tukar jika user salah input
                [$request->tgl_awal, $request->tgl_akhir] = [$request->tgl_akhir, $request->tgl_awal];
            }
    
            $penjualans->whereBetween('penjualan_tanggal', [
                $request->tgl_awal.' 00:00:00',
                $request->tgl_akhir.' 23:59:59',
            ]);
        } elseif ($request->filled('tgl_awal')) {
            // hanya tanggal awal → >= tgl_awal
            $penjualans->where('penjualan_tanggal', '>=', $request->tgl_awal.' 00:00:00');
        } elseif ($request->filled('tgl_akhir')) {
            // hanya tanggal akhir → <= tgl_akhir
            $penjualans->where('penjualan_tanggal', '<=', $request->tgl_akhir.' 23:59:59');
        }
    
        /* ───────────────────────────────
           4. Kembalikan ke DataTables
        ─────────────────────────────── */
        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($p) {
                $btn  = '<button onclick="modalAction(\''.url('/penjualan/'.$p->penjualan_id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/'.$p->penjualan_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    

// Menampilkan halaman form tambah penjualan
public function create()
{
    $breadcrumb = (object)[
        'title' => 'Tambah Penjualan',
        'list'  => ['Home', 'Penjualan', 'Tambah']
    ];

    $page = (object)[ 'title' => 'Tambah penjualan baru' ];
    $activeMenu = 'penjualan';

    // data user (kasir)
    $users   = UserModel::all();

    // data barang  + total stok tiap barang
    // pastikan relasi stok() sudah ada di BarangModel
    $barangs = BarangModel::select('m_barang.*')
          ->leftJoin('t_stok','t_stok.barang_id','=','m_barang.barang_id')
          ->selectRaw('COALESCE(SUM(t_stok.stok_jumlah),0) as stok_sum_stok_jumlah')
          ->groupBy('m_barang.barang_id')
          ->get();

    return view('penjualan.create', [
        'breadcrumb' => $breadcrumb,
        'page'       => $page,
        'activeMenu' => $activeMenu,
        'users'      => $users,
        'barangs'    => $barangs   // ⬅️ kirim ke view
    ]);
}

    // Menyimpan data penjualan baru
    public function store(Request $r)
    {
        $rules = [
            'user_id'           => ['required','integer'],
            'pembeli'           => ['required','string','max:100'],
            'penjualan_kode'    => ['required','string','max:20','unique:t_penjualan,penjualan_kode'],
            'penjualan_tanggal' => ['required','date'],
            'details'           => ['required','array','min:1'],
            'details.*.barang_id'     => ['required','integer'],
            'details.*.jumlah' => ['required','integer','min:1'],
            'details.*.harga'  => ['required','numeric'],
        ];
        $r->validate($rules);
    
        DB::beginTransaction();
        try{
            // ── Header
            $penjualan = PenjualanModel::create($r->only([
                'user_id','pembeli','penjualan_kode','penjualan_tanggal'
            ]));
    
            // ── Detail + pengurangan stok
            foreach($r->details as $i=>$d){
                $stok = StokModel::where('barang_id',$d['barang_id'])->lockForUpdate()->first();
    
                if(!$stok || $stok->stok_jumlah < $d['jumlah']){
                    DB::rollBack();
                    return back()->withInput()
                          ->withErrors(['details.'.$i.'.jumlah'=>'Stok tidak cukup!']);
                }
    
                $stok->decrement('stok_jumlah',$d['jumlah']);
    
                PenjualanDetailModel::create([
                    'penjualan_id'   => $penjualan->penjualan_id,
                    'barang_id'      => $d['barang_id'],
                    'jumlah'  => $d['jumlah'],
                    'harga'   => $d['harga'],
                ]);
            }
            DB::commit();
            return redirect('/penjualan')->with('success','Transaksi tersimpan');
        }catch(\Throwable $e){
            DB::rollBack();
            return back()->with('error',$e->getMessage());
        }
    }
    

    // Menampilkan detail penjualan
    public function show(string $id)
    {
        // Gunakan with('user') agar info user (kasir) dapat ditampilkan
        $penjualan = PenjualanModel::with('user')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list'  => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'penjualan'  => $penjualan,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit penjualan
    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list'  => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit penjualan'
        ];

        $activeMenu = 'penjualan';

        // Ambil data user untuk mengisi dropdown user
        $users = UserModel::all();

        return view('penjualan.edit', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'penjualan'  => $penjualan,
            'users'      => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data penjualan
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id'          => 'required|integer',
            'pembeli'          => 'required|string|max:100',
            'penjualan_kode'   => 'required|string|max:20|unique:t_penjualan,penjualan_kode,'.$id.',penjualan_id',
            'penjualan_tanggal'=> 'required|date',
        ]);

        $penjualan = PenjualanModel::find($id);
        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $penjualan->update([
            'user_id'          => $request->user_id,
            'pembeli'          => $request->pembeli,
            'penjualan_kode'   => $request->penjualan_kode,
            'penjualan_tanggal'=> $request->penjualan_tanggal,
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    // Menghapus data penjualan
    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            PenjualanModel::destroy($id);
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika ada constraint foreign key, dsb.
            return redirect('/penjualan')->with(
                'error',
                'Data penjualan gagal dihapus karena masih ada data lain yang terkait'
            );
        }
    }

    
    public function create_ajax()
    {
        $users = UserModel::all();
        $barangs = BarangModel::select('m_barang.*')
        ->leftJoin('t_stok','t_stok.barang_id','=','m_barang.barang_id')
        ->selectRaw('COALESCE(SUM(t_stok.stok_jumlah),0) as stok_sum_stok_jumlah')
        ->groupBy('m_barang.barang_id')
        ->get();
        return view('penjualan.create_ajax')->with(['users' => $users, 'barangs' => $barangs]);
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            'user_id'           => ['required', 'integer'],
            'pembeli'           => ['required', 'string', 'max:100'],
            'penjualan_kode'    => ['required', 'string', 'max:20', 'unique:t_penjualan,penjualan_kode'],
            'penjualan_tanggal' => ['required', 'date'],
            'details'           => ['required', 'array', 'min:1'],
            'details.*.barang_id' => ['required', 'integer'],
            'details.*.jumlah'    => ['required', 'integer', 'min:1'],
            'details.*.harga'     => ['required', 'numeric'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            $penjualan = PenjualanModel::create($request->only([
                'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'
            ]));

            foreach ($request->details as $index => $detail) {
                $stokBarang = StokModel::where('barang_id', $detail['barang_id'])->first();

                if (!$stokBarang || $stokBarang->stok_jumlah < 1) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => 'Stok barang tidak tersedia atau habis pada baris ke-' . ($index + 1)
                    ]);
                }

                if ($detail['jumlah'] > $stokBarang->stok_jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => 'Jumlah yang diminta melebihi stok yang tersedia pada baris ke-' . ($index + 1)
                    ]);
                }

                $stokBarang->stok_jumlah -= $detail['jumlah'];
                $stokBarang->save();

                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id'    => $detail['barang_id'],
                    'jumlah'       => $detail['jumlah'],
                    'harga'        => $detail['harga'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data penjualan beserta detail berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan data. ' . $e->getMessage()
            ]);
        }
    }

    public function show_ajax($id)
{
    $penjualan = PenjualanModel::with(['user', 'penjualanDetail.barang'])->find($id);

    if (!$penjualan) {
        return response()->json(['error' => 'Penjualan not found'], 404);
    }

    return view('penjualan.show_ajax', [
        'penjualan' => $penjualan,
        'penjualanDetail' => $penjualan->penjualanDetail
    ]);
}

    public function confirm_ajax($id)
    {
        $penjualan = PenjualanModel::with(['penjualanDetail.barang', 'user'])->find($id);
        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data penjualan beserta detailnya berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }
        }
    }

    public function export_excel()
{
    // data header + detail (sekali query pakai eager‑load)
    $data = PenjualanModel::with(['user','penjualanDetail.barang'])
            ->orderBy('penjualan_tanggal')
            ->get();

    /* ─── Spreadsheet ─── */
    $sheet = (new Spreadsheet)->getActiveSheet();

    // judul kolom
    $sheet->fromArray(
        ['No','Tanggal','Kode','Kasir','Pembeli','Total (Rp)'],
        null,'A1',true
    );

    $row = 2;
    foreach($data as $i=>$pj){
        $total = $pj->penjualanDetail->sum(fn($d)=>$d->jumlah * $d->harga);
        $sheet->fromArray([
            $i+1,
            $pj->penjualan_tanggal,
            $pj->penjualan_kode,
            $pj->user->nama ?? '-',
            $pj->pembeli,
            $total
        ], null, 'A'.$row, true);
        $row++;
    }

    // lebar kolom
    foreach(['A'=>5,'B'=>20,'C'=>12,'D'=>25,'E'=>25,'F'=>18] as $col=>$w){
        $sheet->getColumnDimension($col)->setWidth($w);
    }
    $sheet->setTitle('Data Penjualan');

    /* download */
    $writer = new Xlsx($sheet->getParent());
    $filename = 'Penjualan_'.date('Y-m-d_His').'.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    $penjualan = PenjualanModel::with(['user','penjualanDetail.barang'])
                ->orderBy('penjualan_tanggal')
                ->get();

    $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan'=>$penjualan]);
    $pdf->setPaper('a4','portrait')
        ->setOption('isRemoteEnabled', true);
    return $pdf->stream('Penjualan_'.date('Y-m-d_H_i_s').'.pdf');
}
    
}