<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
        public function index()
        {
            // Sederhana: Tampilkan view index, Datatables akan memanggil route kategori/list
            $activeMenu  = 'kategori';
            $breadcrumb  = (object)[
                'title' => 'Data Kategori',
                'list'  => ['Data Barang','Kategori Barang']
            ];
            // View index (blade) akan memanggil datatables ke url('kategori/list')
            return view('kategori.index', compact('activeMenu','breadcrumb'));
        }
    
        public function list(Request $request)
        {
            // Sumber data utk Datatables (Yajra)
            // Atau boleh pakai DB::table('m_kategori')
            $kategori = KategoriModel::select('kategori_id','kategori_kode','kategori_nama','created_at');
    
            // Return JSON ke Datatables
            return DataTables::of($kategori)
                ->addIndexColumn() // menambahkan kolom nomor
                ->addColumn('aksi', function($row){
                    // Link Detail
                    $btn  = '<a href="'.url('kategori/'.$row->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                    // Link Edit
                    $btn .= '<a href="'.url('kategori/'.$row->kategori_id.'/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                    // Form Hapus
                    $btn .= '<form action="'.url('kategori/'.$row->kategori_id).'" method="POST" class="d-inline">'
                          . csrf_field() . method_field('DELETE')
                          . '<button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin?\');">Hapus</button></form>';
    
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        public function create()
        {
            $activeMenu = 'kategori';
            $breadcrumb = (object)[
                'title' => 'Tambah Kategori',
                'list'  => ['Data Barang','Kategori Barang','Tambah']
            ];
    
            return view('kategori.create', compact('activeMenu','breadcrumb'));
        }
    
        public function store(Request $request)
        {
            $activeMenu = 'kategori'; // meski kita redirect, tambahkan agar seragam
    
            // Validasi input
            $request->validate([
                'kategori_kode' => 'required|max:10',
                'kategori_nama' => 'required|max:100'
            ]);
    
            // Simpan ke DB
            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama
            ]);
    
            return redirect('kategori')->with('success','Data kategori berhasil ditambahkan!');
        }
        
        public function show($id)
        {
            $activeMenu = 'kategori';
            $breadcrumb = (object)[
                'title' => 'Detail Kategori',
                'list'  => ['Data Barang','Kategori Barang','Detail']
            ];
    
            $kategori = KategoriModel::find($id);
            return view('kategori.show', compact('kategori','activeMenu','breadcrumb'));
        }
        
        public function edit($id)
        {
            $activeMenu = 'kategori';
            $breadcrumb = (object)[
                'title' => 'Edit Kategori',
                'list'  => ['Data Barang','Kategori Barang','Edit']
            ];
    
            $kategori = KategoriModel::find($id);
            return view('kategori.edit', compact('kategori','activeMenu','breadcrumb'));
        }
    
        public function update(Request $request, $id)
        {
            // Validasi
            $request->validate([
                'kategori_kode' => 'required|max:10',
                'kategori_nama' => 'required|max:100'
            ]);
    
            $kategori = KategoriModel::find($id);
            if (!$kategori) {
                return redirect('kategori')->with('error','Data tidak ditemukan!');
            }
    
            $kategori->update([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama
            ]);
    
            return redirect('kategori')->with('success','Data kategori berhasil diupdate!');
        }
        
        public function destroy($id)
        {
            $kategori = KategoriModel::find($id);
            if (!$kategori) {
                return redirect('kategori')->with('error','Data tidak ditemukan!');
            }
    
            $kategori->delete();
            return redirect('kategori')->with('success','Data kategori berhasil dihapus!');
        }
    
    
}
