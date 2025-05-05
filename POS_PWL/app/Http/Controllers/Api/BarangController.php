<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * GET /api/barangs
     */
    public function index()
    {
        $barangs = BarangModel::all()
            ->transform(fn($b) => tap($b, fn(&$i) => 
                $i->image = url('storage/barang/'.$i->image)
            ));

        return response()->json($barangs, 200);
    }

    /**
     * POST /api/barangs
     * (upload image + simpan record)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id'  => 'required|exists:m_kategori,kategori_id',
            'barang_kode'  => 'required|string|unique:m_barang,barang_kode',
            'barang_nama'  => 'required|string|max:255',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric|gte:harga_beli',
            'image'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // simpan file
        $file     = $request->file('image');
        $filename = $file->hashName();
        $file->storeAs('public/barang', $filename);

        // buat record
        $barang = BarangModel::create([
            'kategori_id' => $data['kategori_id'],
            'barang_kode' => $data['barang_kode'],
            'barang_nama' => $data['barang_nama'],
            'harga_beli'  => $data['harga_beli'],
            'harga_jual'  => $data['harga_jual'],
            'image'       => $filename,
        ]);
        // kembalikan full URL
        $barang->image = url('storage/barang/'.$filename);

        return response()->json($barang, 201);
    }

    /**
     * GET /api/barangs/{barang}
     */
    public function show(BarangModel $barang)
    {
        $barang->image = url('storage/barang/'.$barang->image);
        return response()->json($barang, 200);
    }

    /**
     * PUT /api/barangs/{barang}
     */
    public function update(Request $request, BarangModel $barang)
    {
        $data = $request->validate([
            'kategori_id'  => 'sometimes|required|exists:m_kategori,kategori_id',
            'barang_nama'  => 'sometimes|required|string|max:255',
            'harga_beli'   => 'sometimes|required|numeric',
            'harga_jual'   => 'sometimes|required|numeric|gte:harga_beli',
            'image'        => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = $file->hashName();
            $file->storeAs('public/barang', $filename);
            $data['image'] = $filename;
        }

        $barang->update($data);
        $barang->image = url('storage/barang/'.$barang->image);

        return response()->json($barang, 200);
    }

    /**
     * DELETE /api/barangs/{barang}
     */
    public function destroy(BarangModel $barang)
    {
        $barang->delete();
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ], 200);
    }
}
