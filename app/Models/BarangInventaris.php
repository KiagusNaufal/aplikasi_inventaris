<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangInventaris extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'tm_barang_inventaris';

    protected $primaryKey = 'br_kode';

    // Set the primary key type to string
    public $incrementing = false;
    protected $keyType = 'string';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'br_kode',
        'jns_barang_kode',
        'user_id',
        'br_nama',
        'vendor_id',
        'br_tgl_nerima',
        'br_tgl_entry',
        'status_barang',
        'kondisi_barang',
    ];

    /**
     * Define the relationship with the `tr_jenis_barang` table.
     */
    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jns_barang_kode', 'jns_barang_kode');
    }

    /**
     * Define the relationship with the `users` table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
    public function peminjaman() {
        return $this->hasMany(DetailPeminjaman::class, 'br_kode', 'br_kode');
    }


}
