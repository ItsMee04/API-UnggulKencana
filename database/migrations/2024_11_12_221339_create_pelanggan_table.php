<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('kodepelanggan', 100);
            $table->string('nik', 20);
            $table->string('nama', 100);
            $table->string('kontak', 100);
            $table->text('alamat');
            $table->date('tanggal');
            $table->integer('poin')->nullable();
            $table->integer('status')->unsigned()->nullable()->default(12);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
