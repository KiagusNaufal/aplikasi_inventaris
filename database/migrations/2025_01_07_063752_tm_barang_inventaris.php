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
        Schema::create('tm_barang_inventaris', function (Blueprint $table) {
            $table->string('br_kode', 12)->primary();
            $table->string('jns_barang_kode', 5)->nullable();
            $table->foreign('jns_barang_kode')->references('jns_barang_kode')->on('tr_jenis_barang')->onDelete('cascade');
            $table->string('user_id', 20)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendor')->onDelete('cascade');
            $table->string('br_nama', 50)->nullable();
            $table->date('br_tgl_nerima')->nullable();
            $table->dateTime('br_tgl_entry')->nullable();
            $table->string('status_barang', 2)->nullable();
            $table->string('kondisi_barang', 2)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tm_barang_inventaris');
    }
};
