<?php

use App\Models\Role;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Scan\ScanController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Jenis\JenisController;
use App\Http\Controllers\Diskon\DiskonController;
use App\Http\Controllers\Nampan\NampanController;
use App\Http\Controllers\Produk\ProdukController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Suplier\SuplierController;
use App\Http\Controllers\Keranjang\KeranjangController;
use App\Http\Controllers\Pelanggan\PelangganController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //MEE
    Route::get('me', [AuthController::class, 'me'])->middleware('onlyAdmin');

    //PEGAWAI
    Route::get('pegawai', [PegawaiController::class, 'index']);
    Route::post('pegawai', [PegawaiController::class, 'store']);
    Route::get('pegawai/{id}', [PegawaiController::class, 'show']);
    Route::patch('pegawai/{id}', [PegawaiController::class, 'update']);
    Route::get('delete-pegawai/{id}', [PegawaiController::class, 'delete']);

    //USER
    Route::get('user', [UserController::class, 'index']);
    Route::patch('user/{id}', [UserController::class, 'store']);

    //JENIS
    Route::get('jenis', [JenisController::class, 'index']);
    Route::post('jenis', [JenisController::class, 'store']);
    Route::get('jenis/{id}', [JenisController::class, 'show']);
    Route::patch('jenis/{id}', [JenisController::class, 'update']);
    Route::get('delete-jenis/{id}', [JenisController::class, 'delete']);

    //PRODUK
    Route::get('produk', [ProdukController::class, 'index']);
    Route::post('produk', [ProdukController::class, 'store']);
    Route::get('produk/{id}', [ProdukController::class, 'show']);
    Route::patch('produk/{id}', [ProdukController::class, 'update']);
    Route::get('delete-produk/{id}', [ProdukController::class, 'delete']);
    Route::get('streambarcode/{id}', [ProdukController::class, 'streamBarcode']);
    Route::get('downloadbarcode/{id}', [ProdukController::class, 'downloadBarcode']);

    //SCAN BARCODE
    Route::get('scanbarcode/{id}', [ScanController::class, 'scanBarcode']);

    //PELANGGAN
    Route::get('pelanggan', [PelangganController::class, 'index']);
    Route::post('pelanggan', [PelangganController::class, 'store']);
    Route::get('pelanggan/{id}', [PelangganController::class, 'show']);
    Route::patch('pelanggan/{id}', [PelangganController::class, 'update']);
    Route::get('delete-pelanggan/{id}', [PelangganController::class, 'delete']);

    //SUPLIER
    Route::get('suplier', [SuplierController::class, 'index']);
    Route::post('suplier', [SuplierController::class, 'store']);
    Route::get('suplier/{id}', [SuplierController::class, 'show']);
    Route::patch('suplier/{id}', [SuplierController::class, 'update']);
    Route::get('delete-suplier/{id}', [SuplierController::class, 'delete']);

    //DISKON
    Route::get('diskon', [DiskonController::class, 'index']);
    Route::post('diskon', [DiskonController::class, 'store']);
    Route::get('diskon/{id}', [DiskonController::class, 'show']);
    Route::patch('diskon/{id}', [DiskonController::class, 'update']);
    Route::get('delete-diskon/{id}', [DiskonController::class, 'delete']);

    //KERANJANG
    Route::get('keranjang', [KeranjangController::class, 'index']);
    Route::post('keranjang/{id}', [KeranjangController::class, 'addtocart']);
    Route::get('delete-keranjang/{id}', [KeranjangController::class, 'deleteKeranjang']);
    Route::get('delete-keranjangAll', [KeranjangController::class, 'deleteKeranjangAll']);

    //NAMPAN
    Route::get('nampan', [NampanController::class, 'index']);
    Route::post('nampan', [NampanController::class, 'store']);
    Route::get('nampan/{id}', [NampanController::class, 'show']);
    Route::patch('nampan/{id}', [NampanController::class, 'update']);
    Route::get('delete-nampan/{id}', [NampanController::class, 'delete']);

    //NAMPAN PRODUK
    Route::get('nampanproduk/{id}', [NampanController::class, 'nampanProdukIndex']);
    Route::post('nampanproduk/{id}', [NampanController::class, 'nampanProdukStore']);
    Route::get('deletenampanProduk/{id}', [NampanController::class, 'nampanProdukDelete']);

    Route::post('logout', [AuthController::class, 'logout']);
});
