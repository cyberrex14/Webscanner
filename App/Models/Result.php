<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'scan_id',
        'type',
        'is_vulnerable',
        'payload'
    ];

    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }
}
