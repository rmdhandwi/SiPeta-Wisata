<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    // Jika menggunakan UUID atau custom id yang bukan auto-increment, atur key-nya
    protected $primaryKey = 'id_kriteria';

    // Jika primary key bukan auto-increment (misal UUID), ubah ini:
    // public $incrementing = false;

    // Jika id bukan integer (misal UUID string)
    // protected $keyType = 'string';

    // Optional: Table name (jika bukan jamak dari nama model)
    protected $table = 'kriteria';

    // Jika tidak menggunakan timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_kriteria',
        'nama_kriteria',
        'bobot_kriteria',
        'tipe_kriteria'
    ];


    public function kriteriaAll()
    {
        return Kriteria::orderBy('nama_kriteria', 'asc')
            ->get();
    }

    public function subkriteria()
    {
        return $this->hasMany(Subkriteria::class, 'kriteria_id', 'id_kriteria');
    }
}
