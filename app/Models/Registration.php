<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];

    protected static function Boot()
    {
        parent::Boot();

        static::creating(function ($model) {

            do {
                $randomId = Str::random(4);
            } while (static::where('id', $randomId)->exists());
            $model->id = $model->id ?? $randomId;

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}