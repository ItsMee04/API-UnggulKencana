<?php

namespace App\Http\Controllers\Diskon;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    public function index()
    {
        $diskon = Diskon::all();
        if ($diskon->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Promo Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Promo Berhasil Ditemukan', 'data' => $diskon]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          =>  'required',
            'diskon'        =>  'required|integer',
            'status'        =>  'required'
        ]);

        $diskon = Diskon::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Promo Berhasil Disimpan', 'data' => $diskon]);
    }

    public function show($id)
    {
        $diskon = Diskon::find($id);

        if (is_null($diskon)) {
            return response()->json(['success' => true, 'message' => 'Data Promo Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Promo Berhasil Ditemukan', 'data' => $diskon]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          =>  'required',
            'diskon'        =>  'required|integer',
            'status'        =>  'required'
        ]);

        $diskon = Diskon::findOrFail($id);

        $diskon->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Promo Berhasil Disimpan', 'data' => $diskon]);
    }

    public function delete($id)
    {
        $diskon = Diskon::find($id);

        $diskon->delete();

        return response()->json(['success' => true, 'message' => 'Data Promo Berhasil Dihapus']);
    }
}
