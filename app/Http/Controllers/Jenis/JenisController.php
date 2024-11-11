<?php

namespace App\Http\Controllers\Jenis;

use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class JenisController extends Controller
{
    public function index()
    {
        $jenis = Jenis::all();

        if ($jenis->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Jenis Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Jenis Berhasil Ditemukan', 'data' => $jenis]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'icon_file'     => 'required|nullable',
            'status'        => 'required',
        ]);

        if ($request->file('icon_file')) {
            $extension = $request->file('icon_file')->getClientOriginalExtension();
            $newAvatar = $request->nama . '.' . $extension;
            $request->file('icon_file')->storeAs('icon', $newAvatar);
            $request['icon'] = $newAvatar;
        }

        $store = Jenis::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Jenis Berhasil Disimpan', 'data' => $store]);
    }

    public function show($id)
    {
        $jenis = Jenis::findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Data Jenis Berhasil Ditemukan', 'data' => $jenis]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required',
            'icon_file'     => 'nullable',
            'status'        => 'required|'
        ]);

        $jenis = Jenis::findOrFail($id);

        if ($request->hasFile('icon_file')) {
            $path     = 'storage/icon/' . $jenis->icon;

            if (File::exists($path)) {
                File::delete($path);
            }

            $extension = $request->file('icon_file')->getClientOriginalExtension();
            $fileName = $jenis->nama . '.' . $extension;
            $request->file('icon_file')->storeAs('icon', $fileName);
            $request['icon'] = $fileName;
        }

        $jenis->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Jenis Berhasil Disimpan', 'data' => $jenis]);
    }

    public function delete($id)
    {
        $jenis = Jenis::findOrFail($id);

        $path     = 'storage/icon/' . $jenis->icon;

        if (File::exists($path)) {
            File::delete($path);
        }

        $jenis->delete();

        return response()->json(['success' => true, 'message' => 'Data Jenis Berhasil Dihapus']);
    }
}
