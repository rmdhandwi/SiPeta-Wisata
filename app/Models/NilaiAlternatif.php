<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiAlternatif extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_alternatif';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'nilai_alternatif';

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_subkriteria',
        'lokasi_wisata_id',
        'subkriteria_id',
        'nilai',
    ];


    public function alternatifAll()
    {
        return NilaiAlternatif::with(['subkriteria', 'lokasi'])
            ->orderBy(
                LokasiWisata::select('nama_lokasi_wisata')
                    ->whereColumn('id_lokasi_wisata', 'nilai_alternatif.lokasi_wisata_id')
            )
            ->get();
    }

    // Opsional jika ada relasi
    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class, 'subkriteria_id', 'id_subkriteria');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiWisata::class, 'lokasi_wisata_id', 'id_lokasi_wisata');
    }
}
