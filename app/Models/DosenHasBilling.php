<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DosenHasBilling extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_dosen_id',
        'billing_id',
    ];

    protected $keyType = 'string'; // Menentukan tipe primary key sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment

    protected $with = ['billingdosen', 'dosen'];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function billingdosen()
    {
        return $this->belongsTo(Billing::class,);
    }
}
