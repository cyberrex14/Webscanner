<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'url',
        'status'
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
