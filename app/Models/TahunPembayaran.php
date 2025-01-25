<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TahunPembayaran extends Model
{
    use HasUuids;
    protected $fillable = ['tahun_akademik','awal_pembayaran', 'akhir_pembayaran'];
    protected $keyType = 'string'; // Menentukan tipe primary key sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment

}
