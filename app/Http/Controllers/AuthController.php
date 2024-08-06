<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        $user = User::all();

        return view('auth.signin', compact('user'));
    }

    public function store(Request $request)
    {
        try{
            $now = Carbon::now();
            $tahun_bulan = $now->year . $now->month;
            $cek = User::count();
            
            if($cek == 0){
                $urut = 100001;
                $kode = 'K-' . $tahun_bulan . $urut;
            }else {
                $ambil = User::all()->last();
                $urut = (int)substr($ambil->kode, -6) + 1;
                $kode = 'K-' . $tahun_bulan . $urut;
            }

            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string',
                'level' => 'required|string',
            ]);
    
            $user = new User;
            $user->kode = $kode;
            $user->nama = $request->nama;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->level = $request->level;
            $user->save();
    
    
            return redirect('login')->with('sukses', 'Berhasil Daftar, Silahkan Login!');
        }catch(\Exception $e){
            return redirect('daftar')->with('status', 'Tidak Berhasil Daftar. Pesan Kesalahan: '.$e->getMessage());
        }
    }

    public function login()
    {
        return view('auth.login');
    }

    public function postlogin(Request $request): RedirectResponse
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            if($user->level == 'admin'){
                return redirect('/admin/dashboard');
            }else{
                return redirect('/kasir/dashboard');
            }
        }
        else {
            return back()->with('gagal', 'Email atau Password salah!');
        }
    }

    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function apiLoginP(Request $request)
    {
        // Add validation rules as per your requirements
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function apiRegister(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create a new user instance
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optionally create a token for the new user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response with user and token
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    public function forgotPw()
    {
        return view('auth.forgotPassword');
    }
}
