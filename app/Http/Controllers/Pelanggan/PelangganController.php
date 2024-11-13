<?php

namespace App\Http\Controllers\Pelanggan;

use App\Models\Produk;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Produk\ProdukController;

class PelangganController extends Controller
{
    private function generateCodePelanggan()
    {
        // Ambil kode customer terakhir dari database
        $lastCustomer = DB::table('pelanggan')
            ->orderBy('kodepelanggan', 'desc')
            ->first();

        // Jika tidak ada customer, mulai dari 1
        $lastNumber = $lastCustomer ? (int) substr($lastCustomer->kodepelanggan, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode customer baru
        $newKodeCustomer = '#C-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeCustomer;
    }

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
        $generateCode = $this->generateCodePelanggan();

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
