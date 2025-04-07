<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;
    
    protected $table = 'vouchers';
    protected $fillable = ['kode', 'diskon', 'tanggal_berlaku', 'tanggal_expired', 'status', 'minimal_belanja', 'kuota'];



    public function store(Request $request)
    {
        dd($request->all()); // Debugging
        $request->validate([
            'kode' => 'required|unique:vouchers',
            'diskon' => 'required|numeric|min:1|max:100',
            'tanggal_berlaku' => 'required|date',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_berlaku',
        ]);

        Voucher::create($request->all());
        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($voucher) {
            $now = Carbon::now();
        
            // Pastikan tanggal_expired memiliki format yang benar
            $tanggal_expired = Carbon::parse($voucher->tanggal_expired);
        
            // Jika kuota habis, maka status = 'habis'
            if ($voucher->kuota == 1) {
                $voucher->status = 'habis';
            } 
            // Jika sudah melewati tanggal expired, status = 'expired'
            elseif ($now->greaterThan($tanggal_expired)) {
                $voucher->status = 'expired';
            } 
            // Jika masih memiliki kuota dan belum expired, status = 'aktif'
            else {
                $voucher->status = 'aktif';
            }
        });
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now();

        if ($this->kuota <= 0) {
            return 'digunakan';
        } elseif ($now->greaterThan($this->tanggal_expired)) {
            return 'expired';
        } else {
            return 'aktif';
        }
    }

}


