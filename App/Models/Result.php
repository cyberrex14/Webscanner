<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scan;
use App\Models\Vulnerability;

class Result extends Model
{
    protected $fillable = [
        'scan_id',
        'type',
        'is_vulnerable',
        'payload'
    ];

    // 🔗 Relasi ke Scan (belongsTo)
    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }

    // 🔥 Relasi ke Vulnerability (hasMany)
    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }
}
