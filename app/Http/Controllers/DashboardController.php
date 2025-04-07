<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::all();
        $barang = Barang::all();
        $transaksi = Transaksi::all();
        $detail = TransaksiDetail::orderBy('created_at', 'desc')->get();
        $barang_diskon = Barang::where('diskon', '>', 0)->get(); 
        $stok_kosong = Barang::where('stok', 0)->get();

        $hari_ini = Carbon::now()->format('Y-m-d');
        $transaksi_hari_ini = Transaksi::whereDate('tanggal', $hari_ini)->get();

        return view('dashboard.index', compact('barang', 'transaksi', 'detail', 'transaksi_hari_ini', 'stok_kosong', 'barang_diskon'));
    }

    public function dashboard()
{
    // Mengambil data untuk dashboard
    $barang = Barang::all();
    $stok_kosong = Barang::where('stok', 0)->get();
    $barang_tersedia = Barang::where('stok', '>', 0)->get(); // Barang dengan stok > 0
    $transaksi = Transaksi::all();
    $transaksi_hari_ini = Transaksi::whereDate('created_at', now()->toDateString())->get();
    $detail = TransaksiDetail::all();

    // Mengirimkan semua variabel ke view
    return view('dashboard', compact('barang', 'stok_kosong', 'transaksi', 'transaksi_hari_ini', 'detail', 'barang_tersedia'));
}
}
