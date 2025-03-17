<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel; 
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    /**
     * Menampilkan halaman utama (DataTables).
     */
    public function index()
    {
        $activeMenu = 'barang';
        $breadcrumb = (object)[
            'title' => 'Data Barang',
            'list'  => ['Data Barang']
        ];

        // View index akan memanggil /barang/list untuk Datatables
        return view('barang.index', compact('activeMenu','breadcrumb'));
    }

    /**
     * Digunakan oleh DataTables untuk memuat data barang (serverSide).
     */
    public function list(Request $request)
    {
        // Jika ingin menampilkan nama kategori, gunakan ->with('kategori')
        $barang = BarangModel::with('kategori')
                    ->select('barang_id','kategori_id','barang_kode','barang_nama','harga_beli','harga_jual','created_at');

        return DataTables::of($barang)
            ->addIndexColumn() // Menambahkan kolom nomor
            ->addColumn('kategori_nama', function($row){
                // Pastikan relasi 'kategori' di model BarangModel
                // Tampilkan nama kategori jika ada
                return $row->kategori ? $row->kategori->kategori_nama : '-';
            })
            ->addColumn('aksi', function($row){
                $btn  = '<a href="'.url('barang/'.$row->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('barang/'.$row->barang_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'.url('barang/'.$row->barang_id).'" method="POST" class="d-inline">'
                      . csrf_field().method_field('DELETE')
                      . '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true);
    }

    /**
     * Menampilkan form tambah data
     */
    public function create()
    {
        $activeMenu = 'barang';
        $breadcrumb = (object)[
            'title' => 'Tambah Barang',
            'list'  => ['Data Barang','Tambah']
        ];

        // Ambil data kategori jika diperlukan dropdown
        $kategori = KategoriModel::all();

        return view('barang.create', compact('activeMenu','breadcrumb','kategori'));
    }

    /**
     * Menyimpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|numeric',
            'barang_kode'  => 'required|max:10',
            'barang_nama'  => 'required|max:100',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
        ]);

        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual
        ]);

        return redirect('barang')->with('success','Data barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail data
     */
    public function show($id)
    {
        $activeMenu = 'barang';
        $breadcrumb = (object)[
            'title' => 'Detail Barang',
            'list'  => ['Data Barang','Detail']
        ];

        // Jika ingin relasi kategori, pakai with('kategori')
        $barang = BarangModel::with('kategori')->find($id);

        return view('barang.show', compact('activeMenu','breadcrumb','barang'));
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $activeMenu = 'barang';
        $breadcrumb = (object)[
            'title' => 'Edit Barang',
            'list'  => ['Data Barang','Edit']
        ];

        $barang   = BarangModel::find($id);
        $kategori = KategoriModel::all(); // untuk dropdown

        return view('barang.edit', compact('activeMenu','breadcrumb','barang','kategori'));
    }

    /**
     * Menyimpan perubahan data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id'  => 'required|numeric',
            'barang_kode'  => 'required|max:10',
            'barang_nama'  => 'required|max:100',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
        ]);

        $barang = BarangModel::find($id);
        if(!$barang){
            return redirect('barang')->with('error','Data barang tidak ditemukan!');
        }

        $barang->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual
        ]);

        return redirect('barang')->with('success','Data barang berhasil diupdate!');
    }

    /**
     * Menghapus data
     */
    public function destroy($id)
    {
        $barang = BarangModel::find($id);
        if(!$barang){
            return redirect('barang')->with('error','Data barang tidak ditemukan!');
        }

        $barang->delete();
        return redirect('barang')->with('success','Data barang berhasil dihapus!');
    }
}
