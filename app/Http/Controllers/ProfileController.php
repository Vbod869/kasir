<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $user = User::find($id);

        return view('user.profile', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user_route = Auth::user();

        try {
            $user = User::findOrFail($id);

            // Validate request data
            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'nullable|min:6|confirmed',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle profile photo upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada (jika diperlukan)
                // Storage::delete('path/to/old/photo.jpg'); // Misal jika Anda menyimpan path di database
                
                // Unggah foto baru
                $file = $request->file('foto');
                $path = $file->store('profile_photos', 'public'); // Simpan di storage/app/public/profile_photos
    
                // Simpan path foto ke database (jika diperlukan)
                // Auth::user()->update(['photo' => $path]);
                return redirect()->back()->with('success', 'Profile updated successfully');
            }
            
            // Update user details
            $user->nama = $request->nama;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect('/' . $user_route->level . '/profile/' . $id)
                ->with('sukses', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/' . $user_route->level . '/profile/' . $id)
                ->with('gagal', 'Data Tidak Berhasil di Edit. Pesan Kesalahan: ' . $e->getMessage());
        }
    }
}
