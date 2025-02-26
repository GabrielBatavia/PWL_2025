<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
