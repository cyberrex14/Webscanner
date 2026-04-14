<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class Scan extends Model
{
    protected $fillable = [
        'url',
        'status'
    ];

    // 🔗 1 scan punya banyak result
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
