<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scan;
use App\Models\Vulnerability;

class Result extends Model
{
    protected $fillable = [
        'scan_id',
        'type',           // XSS, SQLi, Header
        'severity',       // 🔥 tambah ini (lebih penting dari is_vulnerable)
        'description',    // 🔥 ganti payload jadi description
    ];

    /**
     * 🔗 belongs to Scan
     */
    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }

    /**
     * 🔥 Relasi ke vulnerability detail (optional advanced)
     */
    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }
}
