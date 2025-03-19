<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Halaman utama (index)
     */
    public function index()
    {
        // Supaya menu sidebar "Data Supplier" aktif
        $activeMenu = 'supplier';

        // Breadcrumb
        $breadcrumb = (object) [
            'title' => 'Data Supplier',
            'list'  => ['Data Barang', 'Data Supplier']
        ];

        // Tampilkan view index; Datatables ambil data via route supplier/list
        return view('supplier.index', compact('activeMenu', 'breadcrumb'));
    }

    /**
     * Metode untuk Datatables (list)
     */
    public function list(Request $request)
    {
        // Ambil data, lalu alias-kan kolom sesuai kebutuhan DataTables
        $suppliers = SupplierModel::select(
            'supplier_id as id',
            'supplier_nama as nama_supplier',
            'supplier_alamat as alamat',
            'created_at'
        );

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('aksi', function($row){
                // Di sini kita sudah punya $row->id
                $btn  = '<a href="'.url('supplier/'.$row->id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('supplier/'.$row->id.'/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'.url('supplier/'.$row->id).'" method="POST" class="d-inline">'
                      . csrf_field() . method_field('DELETE')
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
        $activeMenu = 'supplier';
        $breadcrumb = (object) [
            'title' => 'Tambah Data Supplier',
            'list'  => ['Data Barang','Data Supplier','Tambah']
        ];

        return view('supplier.create', compact('activeMenu','breadcrumb'));
    }

    /**
     * Proses simpan data baru
     */
    public function store(Request $request)
    {
        // Validasi form (sesuaikan name input di Blade: nama_supplier, alamat)
        $request->validate([
            'nama_supplier' => 'required|max:100',
            'alamat'        => 'required'
        ]);

        // Simpan ke DB (mapping ke kolom aslinya)
        SupplierModel::create([
            'supplier_nama'   => $request->nama_supplier,
            'supplier_alamat' => $request->alamat
        ]);

        return redirect('supplier')->with('success','Data supplier berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail data
     */
    public function show($id)
    {
        $activeMenu = 'supplier';
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list'  => ['Data Barang','Data Supplier','Detail']
        ];

        $supplier = SupplierModel::find($id);
        return view('supplier.show', compact('supplier','activeMenu','breadcrumb'));
    }

    /**
     * Menampilkan form edit data
     */
    public function edit($id)
    {
        $activeMenu = 'supplier';
        $breadcrumb = (object) [
            'title' => 'Edit Data Supplier',
            'list'  => ['Data Barang','Data Supplier','Edit']
        ];

        $supplier = SupplierModel::find($id);
        return view('supplier.edit', compact('supplier','activeMenu','breadcrumb'));
    }

    /**
     * Proses update data
     */
    public function update(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'nama_supplier' => 'required|max:100',
            'alamat'        => 'required'
        ]);

        $supplier = SupplierModel::find($id);
        if (!$supplier) {
            return redirect('supplier')->with('error','Data tidak ditemukan!');
        }

        // Update kolom DB dengan field dari request
        $supplier->update([
            'supplier_nama'   => $request->nama_supplier,
            'supplier_alamat' => $request->alamat
        ]);

        return redirect('supplier')->with('success','Data supplier berhasil diupdate!');
    }

    /**
     * Menghapus data
     */
    public function destroy($id)
    {
        $supplier = SupplierModel::find($id);
        if (!$supplier) {
            return redirect('supplier')->with('error','Data tidak ditemukan!');
        }

        $supplier->delete();
        return redirect('supplier')->with('success','Data supplier berhasil dihapus!');
    }
}
