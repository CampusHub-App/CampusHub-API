<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'nomor_telepon',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function Boot()
    {
        parent::Boot();

        static::creating(function ($model) {

            do {
                $randomId = Str::random(6);
            } while (static::where('id', $randomId)->exists());
            $model->id = $model->id ?? $randomId;

        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'user_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

}
