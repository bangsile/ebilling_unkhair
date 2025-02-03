<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LogJob extends Model
{
    use HasUuids;

    protected $fillable = [
        'trx_id',
        'no_va',
        'nama',
        'metode_pembayaran',
        'job_result',
    ];
}
