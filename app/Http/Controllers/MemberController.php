<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    // Menampilkan halaman daftar member
    public function index()
    {
        $members = Member::all();
        return view('member.index', compact('members'));
    }

    // Menyimpan data member ke database
    public function store(Request $request)
    {
        $request->validate([
            'kode_member' => 'required|unique:members,kode_member',
            'nama' => 'required',
            'masa_aktif' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
            'poin' => 'required|integer'
        ]);

        Member::create([
            'kode_member' => $request->kode_member,
            'nama' => $request->nama,
            'masa_aktif' => $request->masa_aktif,
            'status' => $request->status,
            'poin' => $request->poin,
        ]);

        return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
    }

    public function applyPoin(Request $request)
{
    $member = Member::where('kode_member', $request->kode)->first();
    
    if (!$member) {
        return response()->json(['message' => 'Kode member tidak ditemukan!'], 400);
    }

    if ($member->masa_aktif < now()) {
        return response()->json(['message' => 'Masa aktif member sudah habis!'], 400);
    }

    $diskon = min($member->poin, $request->total); // Diskon maksimal sebesar total belanja
    $total_setelah_diskon = $request->total - $diskon;

    return response()->json([
        'diskon' => $diskon,
        'total_setelah_diskon' => $total_setelah_diskon
    ]);
}
}
