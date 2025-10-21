<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingTransaction extends Model
{
    protected $fillable =[ // harus ada fillablle agar create bisa bekerja
        'plate',
        'vehicle_type',
        'entry_time',
        'exit_time',
        'duration_hours',
        'amount',
        'qr_code',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];
}
