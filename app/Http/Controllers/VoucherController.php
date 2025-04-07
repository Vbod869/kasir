<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return view('voucher.voucher_admin', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:vouchers',
            'diskon' => 'required|numeric|min:1|max:100',
            'tanggal_berlaku' => 'required|date',
            'tanggal_expired' => 'required|date|after:tanggal_berlaku',
            'minimal_belanja' => 'required|numeric|min:0',
        ]);

        Voucher::create($request->all());
        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
    return view('voucher.voucher_admin_edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:vouchers,kode,' . $voucher->id,
            'diskon' => 'required|numeric|min:1|max:100',
            'tanggal_berlaku' => 'required|date',
            'tanggal_expired' => 'required|date|after:tanggal_berlaku',
            'minimal_belanja' => 'required|numeric|min:0',
        ]);

        $voucher->update([
            'kode' => $request->kode,
            'diskon' => $request->diskon,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'tanggal_expired' => $request->tanggal_expired,
            'minimal_belanja' => $request->minimal_belanja,
        ]);

        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return redirect()->route('voucher.index')->with('success', 'Voucher berhasil dihapus.');
    }

   

    public function cekVoucher($kode)
{
    $voucher = Voucher::where('kode', $kode)
        ->where('tanggal_berlaku', '<=', Carbon::now()) // Pastikan voucher sudah aktif
        ->where(function ($query) {
            $query->whereNull('tanggal_expired')
                  ->orWhere('tanggal_expired', '>=', Carbon::now());
        })
        ->where('kuota', '>', 0)
        ->where('status', 'aktif')
        ->first();

    if (!$voucher) {
        return response()->json(['message' => 'Voucher tidak valid atau sudah habis'], 400);
    }

    return response()->json([
        'kode' => $voucher->kode,
        'diskon' => $voucher->diskon,
        'status' => 'valid'
    ]);
}

public function applyVoucher(Request $request)
{
    $request->validate([
        'kode' => 'required',
        'total' => 'required|numeric|min:1',
    ]);

    $voucher = Voucher::where('kode', $request->kode)
        ->where('tanggal_berlaku', '<=', Carbon::now())
        ->where(function ($query) {
            $query->whereNull('tanggal_expired')
                  ->orWhere('tanggal_expired', '>=', Carbon::now());
        })
        ->where('kuota', '>', 0)
        ->where('status', 'aktif')
        ->first();

    if (!$voucher) {
        return response()->json(['message' => 'Voucher tidak valid atau sudah habis'], 400);
    }

    // Cek apakah total belanja memenuhi minimal belanja
    if ($request->total < $voucher->minimal_belanja) {
        return response()->json([
            'message' => 'Minimal belanja Rp ' . number_format($voucher->minimal_belanja, 0, ',', '.') . ' untuk menggunakan voucher ini.'
        ], 400);
    }

    // Hitung total setelah diskon
    $total_setelah_diskon = $request->total * (1 - ($voucher->diskon / 100));

    // Kurangi kuota voucher
    $voucher->decrement('kuota');

    // Cek status voucher setelah kuota dikurangi
    if ($voucher->kuota <= 0) {
        $voucher->status = 'habis';
    } elseif ($voucher->tanggal_expired && Carbon::now()->greaterThan($voucher->tanggal_expired)) {
        $voucher->status = 'expired';
    } else {
        $voucher->status = 'aktif';
    }

    $voucher->save(); // Simpan perubahan status

    return response()->json([
        'message' => 'Voucher berhasil diterapkan',
        'kode' => $voucher->kode,
        'diskon' => $voucher->diskon,
        'total_setelah_diskon' => round($total_setelah_diskon, 2) // Bulatkan agar lebih rapi
    ]);
}



}
