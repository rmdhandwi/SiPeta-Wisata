<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTopsis extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_jenis_wisata';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    protected $fillable = [
        'id_jenis_wisata',
        'nama_jenis_wisata',
        'keterangan',
    ];

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'jenis_wisata';

    public function hasilAll()
    {
        return JenisWisata::select('id_jenis_wisata', 'nama_jenis_wisata', 'keterangan')
            ->orderBy('nama_jenis_wisata', 'asc')
            ->get();
    }

    public function lokasiWisata()
    {
        return $this->hasMany(LokasiWisata::class, 'jenis_wisata_id', 'id_jenis_wisata');
    }
}
