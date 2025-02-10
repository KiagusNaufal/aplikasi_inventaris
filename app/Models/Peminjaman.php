<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'tm_peminjaman';
    protected $primaryKey = 'pb_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'pb_id',
        'user_id',
        'siswa_id',
        'pb_tgl',
        'pb_harus_kembali_tgl',
        'pb_status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'pb_id', 'pb_id');
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'pb_id', 'pb_id');
    }


}