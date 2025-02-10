<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nama_jurusan',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'jurusan_id', 'id');
    }

}
