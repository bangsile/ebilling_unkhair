<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'user_simak',
        'is_active',
        'detail'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $keyType = 'string'; // Menentukan tipe primary key sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment
    protected $primaryKey = 'id'; // Menonaktifkan auto-increment

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Mengisi UUID secara otomatis saat data dibuat
    //     static::creating(function ($model) {
    //         if (empty($model->id)) {
    //             $model->id = Str::uuid()->toString();
    //         }
    //     });
    // }

    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
