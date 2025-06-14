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
        'date',
        'start_time',
        'end_time',
        'deskripsi',
        'foto_event',
        'foto_pembicara',
        'pembicara',
        'role',
        'available_slot',
        'tempat',
        'user_id',
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

    protected static function booted()
    {
        static::saving(function ($event) {
            if ($event->tempat == null) {
                $event->tempat = 'Online';
            }
        });
    }

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function getStartTimeAttribute($value)
    {
        return substr($value, 0, 5);
    }

    public function getEndTimeAttribute($value)
    {
        return substr($value, 0, 5);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}