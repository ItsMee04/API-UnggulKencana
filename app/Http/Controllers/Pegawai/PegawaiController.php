<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();

        if ($pegawai->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Pegawai Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Ditemukan', 'data' => $pegawai]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'           =>  'required|unique:pegawai',
            'nama'          => 'required',
            'kontak'        => 'required',
            'jabatan_id'    => 'required|' . Rule::in(Jabatan::where('status', 1)->pluck('id')),
            'alamat'        => 'required',
            'status'        => 'required',
            'avatar'        => 'mimes:png,jpg,jpeg',
        ]);

        if ($request->file('avatar')) {
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $newAvatar = $request->nip . '.' . $extension;
            $request->file('avatar')->storeAs('Avatar', $newAvatar);
            $request['avatar'] = $newAvatar;
        }

        $store = Pegawai::create($request->all());

        $pegawai_id = Pegawai::where('nip', '=', $request->nip)->first()->id;

        if ($store) {
            User::create([
                'pegawai_id' => $pegawai_id,
                'role_id'    => $request->jabatan_id,
                'status'     => $request->status
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Disimpan', 'data' => $store]);
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Ditemukan', 'data' => $pegawai]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required',
            'kontak'        => 'required',
            'jabatan_id'    => 'required|' . Rule::in(Jabatan::where('status', 1)->pluck('id')),
            'alamat'        => 'required',
            'status'        => 'required',
            'avatar_file'   => 'mimes:png,jpg,jpeg',
        ]);

        $pegawai = Pegawai::findOrFail($id);

        if ($request->hasFile('avatar_file')) {
            $path     = 'storage/avatar/' . $pegawai->avatar;

            if (File::exists($path)) {
                File::delete($path);
            }

            $extension = $request->file('avatar_file')->getClientOriginalExtension();
            $fileName = $pegawai->nip . '.' . $extension;
            $request->file('avatar_file')->storeAs('avatar', $fileName);
            $request['avatar'] = $fileName;
        }

        $pegawai->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Disimpan', 'data' => $pegawai]);
    }

    public function delete($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $path     = 'storage/avatar/' . $pegawai->avatar;

        if (File::exists($path)) {
            File::delete($path);
        }

        $deletePegawai = $pegawai->delete();

        if ($deletePegawai) {
            User::where('pegawai_id', $id)->delete();
        }

        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Dihapus']);
    }
}
