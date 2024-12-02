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
        Schema::table('galeri', function (Blueprint $table) {
            // Drop foreign key constraint lama
            $table->dropForeign(['buku_id']);
            
            // Buat foreign key baru tanpa cascade delete
            $table->foreign('buku_id')
                  ->references('id')
                  ->on('books')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropForeign(['buku_id']);
            
            $table->foreign('buku_id')
                  ->references('id')
                  ->on('books')
                  ->onDelete('cascade');
        });
    }
};
