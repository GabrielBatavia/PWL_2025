<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    /**
     * Menampilkan daftar level
     */
    public function index()
    {
        // Ambil data dari DB (boleh pakai Eloquent: $data = LevelModel::all();)
        $data = DB::select('SELECT * FROM m_level');

        // Set menu aktif
        $activeMenu = 'level';

        // Set breadcrumb
        $breadcrumb = (object) [
            'title' => 'Data Level User',
            'list'  => ['Data Pengguna', 'Level User']
        ];

        // Kirim ke view
        return view('level.index', [
            'data'       => $data,
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Menampilkan form tambah data
     */
    public function create()
    {
        $activeMenu = 'level';
        $breadcrumb = (object) [
            'title' => 'Tambah Data Level',
            'list'  => ['Data Pengguna', 'Level User', 'Tambah']
        ];

        return view('level.create', compact('activeMenu','breadcrumb'));
    }

    /**
     * Menyimpan data baru ke DB
     */
    public function store(Request $request)
    {
        // Validasi form
        $request->validate([
            'level_kode' => 'required|max:10',
            'level_nama' => 'required|max:50',
        ]);

        // Simpan ke DB (pakai Eloquent)
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        // Redirect ke daftar data dengan pesan sukses
        return redirect('level')->with('success','Data level berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu data level
     */
    public function show($id)
    {
        $activeMenu = 'level';
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Level User',
            'list'  => ['Data Pengguna', 'Level User', 'Detail']
        ];

        return view('level.show', compact('level','activeMenu','breadcrumb'));
    }

    /**
     * Menampilkan form edit data level
     */
    public function edit($id)
    {
        $activeMenu = 'level';
        $level = LevelModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit Data Level',
            'list'  => ['Data Pengguna', 'Level User', 'Edit']
        ];

        return view('level.edit', compact('level','activeMenu','breadcrumb'));
    }

    /**
     * Menyimpan perubahan data level
     */
    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'level_kode' => 'required|max:10',
            'level_nama' => 'required|max:50',
        ]);

        // Cari data
        $level = LevelModel::find($id);
        if (!$level) {
            return redirect('level')->with('error','Data level tidak ditemukan!');
        }

        // Update data
        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('level')->with('success','Data level berhasil diupdate!');
    }

    /**
     * Menghapus data level
     */
    public function destroy($id)
    {
        $level = LevelModel::find($id);
        if (!$level) {
            return redirect('level')->with('error','Data level tidak ditemukan!');
        }

        try {
            $level->delete();

            return redirect('/level')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/level')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
