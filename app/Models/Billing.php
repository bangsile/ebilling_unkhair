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

    public function scopeSearch($query, $search): void
    {
        $query->where(
            function ($query) use ($search) {
                $query->where('jenis_bayar', 'like', "%{$search}%");
            }
        );
    }
}
