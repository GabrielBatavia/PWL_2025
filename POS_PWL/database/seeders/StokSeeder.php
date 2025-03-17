<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $barangIds = DB::table('m_barang')->pluck('barang_id')->toArray();

        if (!empty($barangIds)) {
            for ($i = 1; $i <= 15; $i++) {
                DB::table('t_stok')->insert([
                    'barang_id' => $barangIds[array_rand($barangIds)],
                    'user_id' => rand(1, 3),
                    'stok_tanggal' => Carbon::now(),
                    'stok_jumlah' => rand(10, 100),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
