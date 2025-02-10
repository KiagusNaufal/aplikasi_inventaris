<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'nis',
        'jurusan_id',
        'kelas_id',
    ];


    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
    public function pinjam()
    {
    return $this->hasMany(Peminjaman::class, 'siswa_id', 'id');
    }
}
