<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * GET /api/penjualans
     */
    public function index()
    {
        $data = PenjualanModel::with(['details.barang'])
            ->get()
            ->map(fn($p) => tap($p, fn(&$i) => 
                $i->details->transform(fn($d) => tap($d, fn(&$x) => 
                    $x->barang->image = url('storage/barang/'.$x->barang->image)
                ))
            ));

        return response()->json($data, 200);
    }

    /**
     * POST /api/penjualans
     * (contoh minimal—sesuaikan validasi & pembuatan detail jika perlu)
     */
    public function store(Request $request)
    {
        $payload = $request->validate([
            'user_id'         => 'required|integer|exists:m_user,user_id',
            'pembeli'         => 'required|string|max:100',
            'penjualan_kode'  => 'required|string|max:50|unique:t_penjualan,penjualan_kode',
        ]);

        $penjualan = PenjualanModel::create([
            'user_id'           => $payload['user_id'],
            'pembeli'           => $payload['pembeli'],
            'penjualan_kode'    => $payload['penjualan_kode'],
            'penjualan_tanggal' => now(),
        ]);

        return response()->json($penjualan, 201);
    }

    /**
     * GET /api/penjualans/{id}
     */
    public function show($id)
    {
        $penjualan = PenjualanModel::with(['details.barang'])
            ->findOrFail($id);

        // juga pastikan image barang jadi URL penuh
        $penjualan->details->transform(fn($d) => tap($d, fn(&$x) => 
            $x->barang->image = url('storage/barang/'.$x->barang->image)
        ));

        return response()->json($penjualan, 200);
    }
}
