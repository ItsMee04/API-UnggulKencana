<?php

namespace App\Http\Controllers\Scan;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function scanBarcode($id)
    {
        $produk = Produk::where('kodeproduk', $id)->first();

        if ($produk) {
            return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Ditemukan', 'data' => $produk]);
        }

        return response()->json(['success' => true, 'message' => 'Data Produk Tidak Ditemukan']);
    }
}
