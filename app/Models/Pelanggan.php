<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table = 'pelanggan';
    protected $fillable = [
        'kodepelanggan',
        'nik',
        'nama',
        'alamat',
        'kontak',
        'tanggal',
        'poin',
        'status'
    ];
}
