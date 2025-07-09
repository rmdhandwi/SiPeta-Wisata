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
        Schema::create('lokasi_wisata', function (Blueprint $table) {
            $table->id('id_lokasi_wisata');
            $table->foreignId('jenis_wisata_id')->constrained('jenis_wisata', 'id_jenis_wisata')->onDelete('cascade');
            $table->string('nama_lokasi_wisata');
            $table->string('fasilitas');
            $table->string('keamanan');
            $table->string('transportasi');
            $table->string('akses_lokasi');
            $table->float('longitude');
            $table->float('latitute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_wisata');
    }
};
