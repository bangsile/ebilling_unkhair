<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisBayar extends Model
{
    use HasUuids;
    protected $fillable = ['kode','keterangan', 'bank'];

}
