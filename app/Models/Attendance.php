<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'fam_id',
        'barangay',
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
        'status',
        'qr_number',
        'barangay',
        'amount',
        'time_in'
    ];

    public function location()
    {
        return $this->belongsTo(LocationInfo::class, 'id');
    }
}
