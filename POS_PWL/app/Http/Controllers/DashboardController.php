<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\KategoriModel;
use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\PenjualanModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount      = UserModel::count();
        $levelCount     = LevelModel::count();
        $kategoriCount  = KategoriModel::count();
        $barangCount    = BarangModel::count();
        $stokCount      = StokModel::sum('stok_jumlah');
        $penjualanCount = PenjualanModel::count();

        // Grafik Penjualan 7 Hari Terakhir (pakai tanggal_penjualan)
        $salesData = PenjualanModel::selectRaw('DATE(penjualan_tanggal) as date, COUNT(*) as total')
            ->where('penjualan_tanggal', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $dataSales = [];
        $range = CarbonPeriod::create(now()->subDays(6), now());
        foreach ($range as $date) {
            $label = $date->format('Y-m-d');
            $labels[] = $label;
            $match = $salesData->firstWhere('date', $label);
            $dataSales[] = $match ? $match->total : 0;
        }

        // Statistik Barang per Kategori
        $kategoriStats = BarangModel::select('kategori_id', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori_id')->get();

        $kategoriLabels = [];
        $kategoriCounts = [];
        foreach ($kategoriStats as $stat) {
            $kategori = KategoriModel::find($stat->kategori_id);
            $kategoriLabels[] = $kategori ? $kategori->kategori_nama : 'Tidak diketahui';
            $kategoriCounts[] = $stat->total;
        }

        // Grafik Stok Barang (nama dan stok diambil dari tabel stok, join barang)
        $stokData = DB::table('t_stok')
            ->join('m_barang', 't_stok.barang_id', '=', 'm_barang.barang_id')
            ->select('m_barang.barang_nama', DB::raw('SUM(t_stok.stok_jumlah) as total_stok'))
            ->groupBy('m_barang.barang_nama')
            ->get();

        $barangLabels = $stokData->pluck('barang_nama');
        $barangStok   = $stokData->pluck('total_stok');
        $breadcrumb = (object)[
            'title' => 'Dashboard',
            'list' => ['Home', 'Dashboard']
        ];
        
        $activeMenu = 'dashboard';

        return view('welcome', compact(
            'userCount', 'levelCount', 'kategoriCount', 'barangCount',
            'stokCount', 'penjualanCount', 'labels', 'dataSales',
            'kategoriLabels', 'kategoriCounts', 'barangLabels', 'barangStok',
            'breadcrumb', 'activeMenu'
        ));
        
}
}