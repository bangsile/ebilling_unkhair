<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BillingMahasiswa extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'trx_id',
        'no_va',
        'nama_bank',
        'jenis_bayar',
        'nominal',
        'tgl_expire',
        'lunas',
        'nama',
        'no_identitas',
        'angkatan',
        'tahun_akademik',
        'kode_prodi',
        'nama_prodi',
        'nama_fakultas',
        'kategori_ukt',
        'jalur',
        'detail',
    ];

    protected $keyType = 'string'; // Menentukan tipe primary key sebagai string
    public $incrementing = false;
}
