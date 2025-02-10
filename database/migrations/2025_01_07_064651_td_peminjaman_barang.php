<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('td_peminjaman_barang', function (Blueprint $table) {
            $table->string('pbd_id', 20)->primary();
            $table->string('pb_id', 20)->nullable();
            $table->foreign('pb_id')->references('pb_id')->on('tm_peminjaman')->onDelete('cascade');
            $table->string('br_kode', 12)->nullable();
            $table->foreign('br_kode')->references('br_kode')->on('tm_barang_inventaris')->onDelete('cascade');
            $table->dateTime('pdb_tgl')->nullable();
            $table->string('pdb_status', 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('td_peminjaman_barang');
    }
};
