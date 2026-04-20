<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Result;
use App\Models\User;

class Scan extends Model
{
    protected $fillable = [
        'user_id',
        'target_url',
        'status',
        'started_at',
        'finished_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    // 🔗 1 scan punya banyak result
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // 🔗 scan milik user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
