<?php

namespace App\Http\Controllers\Produk;

use App\Models\Jenis;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdukController extends Controller
{
    public function generateKode()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomCode;
    }

    public function index()
    {
        $produk = Produk::all();

        if ($produk->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Produk Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Ditemukan', 'data' => $produk]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          =>  'required',
            'jenis_id'      =>  'required|' . Rule::in(Jenis::where('status', 1)->pluck('id')),
            'harga_jual'    =>  'integer',
            'harga_beli'    =>  'integer',
            'keterangan'    =>  'string',
            'berat'         =>  [
                'required',
                'regex:/^\d+\.\d{1}$/'
            ],
            'karat'         =>  'required|integer',
            'image_file'    =>  'nullable|mimes:png,jpg',
            'status'        =>  'required'
        ]);

        $kodeproduk = $this->generateKode();

        $content = QrCode::format('png')->size(300)->generate($kodeproduk); // Ini menghasilkan data PNG sebagai string

        // Tentukan nama file
        $fileName = 'barcode/' . $kodeproduk . '.png';

        // Simpan file ke dalam storage/public/barcode/
        Storage::put($fileName, $content);

        if ($request->file('image_file')) {
            $extension = $request->file('image_file')->getClientOriginalExtension();
            $fileName = $kodeproduk . '.' . $extension;
            $request->file('image_file')->storeAs('produk', $fileName);
            $request['image'] = $fileName;
        }

        $request['kodeproduk'] = $kodeproduk;
        $produk = Produk::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan', 'data' => $produk]);
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        if (is_null($produk)) {
            return response()->json(['success' => true, 'message' => 'Data Produk Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Ditemukan', 'data' => $produk]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          =>  'required',
            'jenis_id'      =>  'required|' . Rule::in(Jenis::where('status', 1)->pluck('id')),
            'harga_jual'    =>  'integer',
            'harga_beli'    =>  'integer',
            'keterangan'    =>  'string',
            'berat'         =>  'required|decimal:1,1',
            'karat'         =>  'required|integer',
            'image_file'    =>  'nullable|mimes:png,jpg',
            'status'        =>  'required'
        ]);

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('image_file')) {
            $path     = 'storage/produk/' . $produk->image;

            if (File::exists($path)) {
                File::delete($path);
            }

            $extension = $request->file('image_file')->getClientOriginalExtension();
            $fileName = $produk->kodeproduk . '.' . $extension;
            $request->file('image_file')->storeAs('produk', $fileName);
            $request['image'] = $fileName;
        }

        $produk->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan', 'data' => $produk]);
    }

    public function delete($id)
    {
        $produk = Produk::findOrFail($id);

        $path     = 'storage/produk/' . $produk->image;
        $path1     = 'storage/barcode/' . $produk->image;

        if (File::exists($path, $path1)) {
            File::delete($path, $path1);
        }

        $produk->delete();

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Dihapus']);
    }

    public function streamBarcode($id)
    {

        $produk = Produk::where('id', $id)->first();
        $filePath = 'storage/barcode/' . $produk->image;

        if (File::exists($filePath)) {
            $file = fopen($filePath, 'r'); // Membuka file untuk dibaca

            return response()->stream(function () use ($file) {
                while (!feof($file)) {
                    echo fread($file, 1024); // Membaca file per 1024 byte
                }
                fclose($file); // Menutup file setelah selesai
            }, 200, [
                'Content-Type' => 'image/png',  // Ganti sesuai dengan tipe file Anda
                'Content-Disposition' => 'attachment; filename="' . $produk . '"',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Data Barcode Tidak Ditemukan']);
    }

    public function downloadBarcode($id)
    {
        $produk = Produk::where('id', $id)->first();
        $filePath = 'storage/barcode/' . $produk->image;

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return response()->json(['success' => false, 'message' => 'Data Barcode Tidak Ditemukan']);
    }
}
