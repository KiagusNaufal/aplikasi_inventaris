<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
    ];

    public function barang()
    {
        return $this->hasMany(BarangInventaris::class, 'vendor_id', 'id');
    }
}
