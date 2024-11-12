<?php

namespace App\Http\Controllers\Suplier;

use App\Models\Suplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Produk\ProdukController;

class SuplierController extends Controller
{
    public function index()
    {
        $suplier = Suplier::all();
        if ($suplier->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Suplier Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Ditemukan', 'data' => $suplier]);
    }

    public function store(Request $request)
    {
        $controller = new ProdukController();
        $generateCode = $controller->generateKode();

        $request->validate([
            'nama'          =>  'required',
            'alamat'        =>  'required',
            'kontak'        =>  'required',
            'status'        =>  'required'
        ]);

        $request['kodesuplier'] = $generateCode;
        $suplier = Suplier::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Disimpan', 'data' => $suplier]);
    }

    public function show($id)
    {
        $suplier = Suplier::find($id);

        if (is_null($suplier)) {
            return response()->json(['success' => true, 'message' => 'Data Suplier Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Ditemukan', 'data' => $suplier]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          =>  'required',
            'kontak'        =>  'required',
            'alamat'        =>  'required|string',
            'status'        =>  'required'
        ]);

        $suplier = Suplier::findOrFail($id);

        $suplier->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Disimpan', 'data' => $suplier]);
    }

    public function delete($id)
    {
        $suplier = Suplier::find($id);

        $suplier->delete();

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Dihapus']);
    }
}
