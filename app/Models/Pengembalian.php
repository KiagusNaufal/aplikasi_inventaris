<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'tm_pengembalian';
    public static function generateTransactionId()
    {
        $year = date('Y');
        $month = date('m');
        $lastRecord = Self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('kembali_id', 'desc')
            ->first();

        $noUrut = $lastRecord ? ((int)substr($lastRecord->kembali_id, -4) + 1) : 1;

        return sprintf('KB%s%s%04d', $year, $month, $noUrut);
    }
    protected $primaryKey = 'kembali_id';
    public $incrementing = false;
    protected $keyType = 'string'; 
    protected $fillable = [
        'kembali_id',
        'pb_id',
        'user_id',
        'kembali_tgl',
        'kembali_status',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'pb_id', 'pb_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
