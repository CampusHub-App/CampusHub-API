<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kategori_id',
        'judul',
        'datetime',
        'deskripsi',
        'foto_event',
        'foto_pembicara',
        'pembicara',
        'role',
        'available_slot',
        'is_offline',
        'tempat',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $randomId = Str::random(12);
            } while (static::where('id', $randomId)->exists());

            $model->id = $model->id ?? $randomId;
        });
    }

    protected $casts = [
        'datetime' => 'datetime',
        'is_offline' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}