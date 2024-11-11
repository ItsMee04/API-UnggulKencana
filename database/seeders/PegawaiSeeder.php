<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pegawai::create([
            'nama'          =>  'Admin',
            'nip'           =>  '0110001',
            'alamat'        =>  'Purwokerto',
            'kontak'        =>  '081390469322',
            'jabatan_id'    =>  1,
            'avatar'        =>  'admin.png',
            'status'        =>  1,
        ]);
    }
}
