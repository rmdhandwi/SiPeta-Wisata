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
        Schema::create('hasil_topse', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->foreignId('lokasi_wisata_id')->constrained('lokasi_wisata', 'id_lokasi_wisata')->onDelete('cascade');
            $table->string('jarak_positif');
            $table->string('jarak_negative');
            $table->string('tipe_peferensi');
            $table->string('rangking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_topse');
    }
};
