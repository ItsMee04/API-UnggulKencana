<?php

namespace App\Http\Controllers\Nampan;

use Carbon\Carbon;
use App\Models\Jenis;
use App\Models\Nampan;
use Faker\Guesser\Name;
use App\Models\NampanProduk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NampanController extends Controller
{
    public function index()
    {
        $nampan = Nampan::with('jenis')->get();

        if ($nampan->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Nampan Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'data' => $nampan]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required',
            'jenis_id'      => 'required|' . Rule::in(Jenis::where('status', 1)->pluck('id')),
            'status'        => 'required',
        ]);

        $nampan = Nampan::create($request->all());

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Disimpan', 'data' => $nampan]);
    }

    public function show($id)
    {
        $nampan = Nampan::with('jenis')->findOrFail($id);
        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'data' => $nampan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'          => 'required',
            'jenis_id'      => 'required|' . Rule::in(Jenis::where('status', 1)->pluck('id')),
            'status'        => 'required',
        ]);

        $nampan = Nampan::with('jenis')->findOrFail($id);

        $nampan->update($request->all());

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Disimpan', 'data' => $nampan]);
    }

    public function delete($id)
    {
        $nampan = Nampan::find($id);

        $nampan->delete();

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Dihapus']);
    }

    public function nampanProdukIndex($id)
    {
        $nampanProduk = DB::table('nampan_produk')
            ->join('nampan', 'nampan_produk.nampan_id', '=', 'nampan.id') // Relasi ke tabel nampan
            ->join('produk', 'nampan_produk.produk_id', '=', 'produk.id') // Relasi ke tabel produk
            ->where('nampan_produk.nampan_id', $id) // Filter berdasarkan nampan_id
            ->select(
                'nampan_produk.id',
                'nampan_produk.nampan_id',
                'nampan_produk.produk_id',
                'nampan_produk.tanggal',
                'nampan_produk.status',
                'nampan.nama as nampan_nama', // Ambil kolom dari tabel nampan
                'produk.kodeproduk',
                'produk.nama as produk_nama',
                'produk.harga_jual',
                'produk.harga_beli',
                'produk.keterangan',
                'produk.berat',
                'produk.karat',
                'produk.image',
                'produk.status as produk_status'
            )
            ->get();

        if ($nampanProduk->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'Data Nampan Tidak Ditemukan']);
        }
        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'data' => $nampanProduk]);
    }

    public function nampanProdukStore(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        // Ambil daftar produk_id yang sudah ada di NampanProduk
        $existingProducts = NampanProduk::whereIn('produk_id', $request->items)
            ->pluck('produk_id')
            ->toArray();

        if (!empty($existingProducts)) {
            return response()->json([
                'message' => 'Beberapa produk sudah ada.',
                'existing_products' => $existingProducts
            ], 400);
        }

        // Tambahkan produk yang belum ada
        $nampanProducts = [];
        foreach ($request->items as $item) {
            $nampanProducts[] = NampanProduk::create([
                'nampan_id' => $id,
                'produk_id' => $item,
                'tanggal'   => Carbon::today()->format('Y-m-d'),
                'status'    => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $nampanProducts
        ]);
    }

    public function nampanProdukDelete($id)
    {
        $nampanProduk = NampanProduk::findOrFail($id);

        $nampanProduk->delete();

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Dihapus']);
    }
}
