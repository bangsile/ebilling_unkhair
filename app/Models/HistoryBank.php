<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HistoryBank extends Model
{
    use HasUuids;
    protected $fillable = ['trx_id', 'no_va', 'nominal', 'nama', 'metode_pembayaran'];
}
