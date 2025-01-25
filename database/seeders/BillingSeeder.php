<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\JenisBayar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisBayar::create([
            'kode' => 'fee-dosen',
            'keterangan' => 'Fee Dosen',
            'bank' => 'BCA',
        ]);
        JenisBayar::create([
            'kode' => 'ukt',
            'keterangan' => 'UKT',
            'bank' => 'BNI',
        ]);
        JenisBayar::create([
            'kode' => 'pemkes',
            'keterangan' => 'Pemeriksaan Kesehatan',
            'bank' => 'BRI',
        ]);
        JenisBayar::create([
            'kode' => 'ipi',
            'keterangan' => 'IPI',
            'bank' => 'MANDIRI',
        ]);
        Billing::factory(30)->create();
    }
}
