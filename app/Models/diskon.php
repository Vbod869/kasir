<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormatRupiah;

class Diskon extends Model
{
    use HasFormatRupiah;

    protected $fillable = ['nama', 'harga', 'stok', 'diskon'];

    // Accessor untuk menghitung harga setelah diskon
    public function getHargaSetelahDiskonAttribute()
    {
        $hargaSetelahDiskon = $this->harga - ($this->harga * $this->diskon / 100);
        return number_format($hargaSetelahDiskon, 0, ',', '.');
    }
}

