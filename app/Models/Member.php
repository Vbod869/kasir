<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members'; // Nama tabel di database

    protected $fillable = [
        'kode_member',
        'nama',
        'masa_aktif',
        'status',
        'poin'
    ];

    protected $dates = ['masa_aktif']; // Supaya otomatis dikenali sebagai tanggal
}
