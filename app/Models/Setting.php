<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Tabel yang digunakan
    protected $table = 'settings';

    // Field yang bisa diisi mass assignment
    protected $fillable = [
        'ticket_type',
        'company_name',
        'address',
        'phone',
        'email',
        'logo',
        'tarif_awal_mobil',
        'tarif_perjam_mobil',
        'tarif_awal_motor',
        'tarif_perjam_motor',
    ];
}
