<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'tr_jenis_barang';

    // Define the primary key of the table
    protected $primaryKey = 'jns_barang_kode';

    // Set the primary key type to string
    public $incrementing = false;
    protected $keyType = 'string';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'jns_barang_kode',
        'jns_barang_nama',
    ];

    /**
     * Define the relationship with the `tm_barang_inventaris` table.
     */
    public function barangInventaris()
    {
        return $this->hasMany(BarangInventaris::class, 'jns_barang_kode', 'jns_barang_kode');
    }
    
}
