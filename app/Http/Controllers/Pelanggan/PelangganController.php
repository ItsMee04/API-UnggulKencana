<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Produk\ProdukController;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        if ($pelanggan->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Pelanggan Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Pelanggan Berhasil Ditemukan', 'data' => $pelanggan]);
    }

    public function store(Request $request)
    {
        $controller = new ProdukController();
        $generateCode = $controller->generateKode();

        $request->validate([
            'nama'          =>  'required',
            'nik'           =>  'required|unique:pelanggan,nik',
            'alamat'        =>  'required',
            'kontak'        =>  'required',
            'keterangan'    =>  'string',
            'tanggal'       =>  'required|date',
            'poin'          =>  'nullable',
            'status'        =>  'required'
        ]);

        $request['kodepelanggan'] = $generateCode;
        $pelanggan = Pelanggan::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Pelanggan Berhasil Disimpan', 'data' => $pelanggan]);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

        if (is_null($pelanggan)) {
            return response()->json(['success' => true, 'message' => 'Data Pelanggan Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Pelanggan Berhasil Ditemukan', 'data' => $pelanggan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          =>  'required',
            'nik'           =>  'required',
            'alamat'        =>  'required',
            'kontak'        =>  'required',
            'keterangan'    =>  'string',
            'tanggal'       =>  'required|date',
            'poin'          =>  'nullable',
            'status'        =>  'required'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Pelanggan Berhasil Disimpan', 'data' => $pelanggan]);
    }

    public function delete($id)
    {
        $pelanggan = Pelanggan::find($id);

        $pelanggan->delete();

        return response()->json(['success' => true, 'message' => 'Data Pelanggan Berhasil Dihapus']);
    }
}
