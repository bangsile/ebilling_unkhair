<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'fakultas_id',
        'kd_prodi',
        'nm_prodi',
        'status',
        'jenjang'
    ];

    public function fakultas()
    {
        return $this->hasOne(Fakultas::class, 'id', 'fakultas_id');
    }

    public function scopeperfakultas($query, $value)
    {
        if ($value) {
            $query->where('fakultas_id', $value);
        }
    }

    public function scopepencarian($query, $value)
    {
        if ($value) {
            $query->where('kd_prodi', 'like', '%' . $value . '%')
                ->orWhere('nm_prodi', 'like', '%' . $value . '%')
                ->orWhere('jenjang', 'like', '%' . $value . '%');
        }
    }
}
