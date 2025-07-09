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
        Schema::create('nilai_alternatif', function (Blueprint $table) {
            $table->id('id_alternatif');
            $table->foreignId('lokasi_wisata_id')->constrained('lokasi_wisata', 'id_lokasi_wisata')->onDelete('cascade');
            $table->foreignId('subkriteria_id')->constrained('subkriteria', 'id_subkriteria')->onDelete('cascade');
            $table->float('nilai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_alternatif');
    }
};
