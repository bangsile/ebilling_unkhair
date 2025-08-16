<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'trx_id',
        'no_va',
        'nama_bank',
        'nama',
        'jenis_bayar',
        'nominal',
        'tgl_expire',
        'lunas',
        'detail',
    ];

    protected $keyType = 'string'; // Menentukan tipe primary key sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment

    public function dosenHasBilling()
    {
        return $this->hasMany(DosenHasBilling::class, 'billing_id');
    }

    public function scopetahun($query, $value)
    {
        if ($value) {
            $query->whereYear('billings.created_at', $value);
        }
    }

    public function scopepembayaran($query, $value): void
    {
        if ($value) {
            $query->where('jenis_bayar', '=', $value);
        }
    }

    public function scopelunas($query, $value): void
    {
        if ($value) {
            $query->where('lunas', '=', $value);
        }
    }

    public function jenis_pembayaran()
    {
        return $this->hasOne(JenisBayar::class, 'kode', 'jenis_bayar');
    }
}
