<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\TransaksiSementara;
use App\Models\TransaksiDetail;
use App\Models\Voucher;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::orderBy('tanggal', 'desc')->get();

        return view('laporan.index', compact('transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'kode_transaksi' => 'required|unique:transaksis,kode_transaksi',
        'total' => 'required|numeric|min:0',
        'bayar' => 'required|numeric|min:0',
        'kembali' => 'required|numeric|min:0',
        'kode_kasir' => 'required|string|max:255',
        'kode_voucher' => 'nullable|string|max:50', // Validasi kode voucher
    ]);

    $voucher = null;
    $diskonVoucher = 0;

    // Jika ada kode voucher, cari voucher yang sesuai
    if ($request->kode_voucher) {
        $voucher = Voucher::where('kode', $request->kode_voucher)->first();
        
        if ($voucher) {
            $diskonVoucher = ($request->total * $voucher->diskon) / 100;
            
            // Kurangi kuota voucher
            $voucher->decrement('kuota');
            
            // Update status voucher jika habis
            if ($voucher->kuota <= 0) {
                $voucher->update(['status' => 'habis']);
            }
        }
    }

    $totalSetelahDiskon = $request->total - $diskonVoucher;
    $kembali = $request->bayar - $totalSetelahDiskon;

    $transaksi = Transaksi::create([
        'kode_transaksi' => $request->kode_transaksi,
        'total' => $totalSetelahDiskon,
        'diskon_voucher' => $diskonVoucher,
        'bayar' => $request->bayar,
        'kembali' => $kembali,
        'kode_kasir' => $request->kode_kasir,
        'voucher_id' => $voucher ? $voucher->id : null, // Simpan ID voucher jika ada
        'kode_voucher' => $request->kode_voucher, // Simpan kode voucher
    ]);

    return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
}

    /**
     * Display the specified resource.
     */
    public function show($kodeTransaksi)
    {
        $data = TransaksiDetail::where('kode_transaksi', $kodeTransaksi)->get();
        
        return view('laporan.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        
    }

    public function print($kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $transaksi_detail = TransaksiDetail::where('kode_transaksi', $kode_transaksi)->get();
        
        // Ambil voucher berdasarkan kode_voucher (jika ada)
        $voucher = !empty($transaksi->kode_voucher) 
            ? Voucher::where('kode', $transaksi->kode_voucher)->first()
            : null;
    
        $pdf = Pdf::loadView('laporan.print', compact('transaksi', 'transaksi_detail', 'voucher'));
        return $pdf->stream();
    }
    

    
    public function cari(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;
        $tanggalSampai = Carbon::parse($sampai)->addDays(1)->format('Y-m-d');
        
        $transaksi = Transaksi::whereBetween('tanggal', [$dari, $tanggalSampai])->get();
        
        return view('laporan.cari',compact('transaksi', 'dari', 'sampai'));
    }
    
    public function printTanggal($dari, $sampai)
    {
        $tanggalSampai = Carbon::parse($sampai)->addDays(1)->format('Y-m-d');
        $transaksi = Transaksi::whereBetween('tanggal', [$dari, $tanggalSampai])->get();
        
        $totalAll = 0;
        foreach($transaksi as $data)
        {
            $totalAll += $data->total;
        }

        $total = number_format($totalAll, 0, ',', '.');

        $pdf = Pdf::loadView('laporan.printTanggal', compact('transaksi', 'dari', 'sampai', 'total'));
        return $pdf->stream();
    }
}
