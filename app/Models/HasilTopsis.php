<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTopsis extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_hasil';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';


    protected $fillable = [
        'lokasi_wisata_id',
        'jarak_positif',
        'jarak_negative',
        'tipe_preferensi',
        'rangking',
    ];

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'hasil_topsis';

    // Relasi ke lokasi_wisata (1 hasil topsis hanya milik 1 lokasi)
    public function lokasiWisata()
    {
        return $this->belongsTo(LokasiWisata::class, 'lokasi_wisata_id', 'id_lokasi_wisata');
    }

    // Static method untuk mengambil semua hasil TOPSIS terurut
    public static function hasilAll()
    {
        return self::with('lokasiWisata')
            ->orderBy('rangking', 'asc')
            ->get();
    }
}
