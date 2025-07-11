<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LokasiWisata extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_lokasi_wisata';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'lokasi_wisata';

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    // protected $fillable = [
    //     'id_lokasi_wisata',
    //     'jenis_wisata_id',
    //     'nama_lokasi_wisata',
    //     'fasilitas',
    //     'keamanan',
    //     'transportasi',
    //     'akses_lokasi',
    //     'longitude',
    //     'latitute'
    // ];

    // public function lokasiWisataAll()
    // {
    //     return LokasiWisata::with('jenisWisata')
    //         ->orderBy('nama_lokasi_wisata', 'asc')
    //         ->get();
    // }

    public function lokasiWisataAll()
    {
        return LokasiWisata::with('jenisWisata:id_jenis_wisata,nama_jenis_wisata')
            ->orderBy('nama_lokasi_wisata', 'asc')
            ->get()
            ->map(function ($item) {
                // Ambil semua data
                $data = collect($item)->toArray();

                // Sisipkan nama_jenis_wisata di awal
                return array_merge([
                    'nama_jenis_wisata' => $item->jenisWisata->nama_jenis_wisata ?? '-',
                ], $data);
            });
    }

    // LokasiWisata.php (Model)
    public static function dataPeta()
    {
        return DB::table('lokasi_wisata as lw')
            ->join('jenis_wisata as jw', 'lw.jenis_wisata_id', '=', 'jw.id_jenis_wisata')
            ->join('hasil_topsis as ht', 'lw.id_lokasi_wisata', '=', 'ht.lokasi_wisata_id')
            ->select(
                'lw.nama_lokasi_wisata as nama',
                'jw.nama_jenis_wisata as jenis',
                'lw.latitude',
                'lw.longitude',
                'lw.fasilitas',
                'lw.keamanan',
                'lw.transportasi',
                'lw.akses_lokasi',
                'ht.rangking as rank',
                'ht.tipe_preferensi as preferensi'
            )
            ->get();
    }



    // Opsional jika ada relasi
    public function jenisWisata()
    {
        return $this->belongsTo(JenisWisata::class, 'jenis_wisata_id', 'id_jenis_wisata');
    }
}
