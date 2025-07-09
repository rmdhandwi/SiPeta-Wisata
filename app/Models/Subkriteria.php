<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_subkriteria';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'subkriteria';

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_subkriteria',
        'kriteria_id',
        'nama_subkriteria',
        'bobot_subkriteria',
        'tipe_subkriteria'
    ];


    public function subkriteriaAll()
    {
        return Subkriteria::with('kriteria')
            ->orderBy(
                Kriteria::select('nama_kriteria')
                    ->whereColumn('kriteria.id_kriteria', 'subkriteria.kriteria_id')
            )
            ->get();
    }      

    // Opsional jika ada relasi
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id', 'id_kriteria');
    }
}
