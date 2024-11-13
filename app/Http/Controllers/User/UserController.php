<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with('pegawai')->get();

        if ($user->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data User Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data User Berhasil Ditemukan', 'data' => $user]);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'email'         => 'required|unique:users',
            'password'      => 'required',
        ]);

        $user = User::where('pegawai_id', $id)->update([
            'email'     =>  $request->email,
            'password'  =>  Hash::make($request->password)
        ]);

        return response()->json(['success' => true, 'message' => 'Data User Berhasil Disimpan', 'data' => $user]);
    }
}
