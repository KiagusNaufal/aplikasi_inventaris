<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'td_peminjaman_barang';
    protected $primaryKey = 'pbd_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'pbd_id',
        'pb_id',
        'br_kode',
        'pdb_tgl',
        'pdb_status'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'pb_id', 'pb_id');
    }
    public function barangInventaris()
    {
        return $this->belongsTo(BarangInventaris::class, 'br_kode', 'br_kode');
    }
}
