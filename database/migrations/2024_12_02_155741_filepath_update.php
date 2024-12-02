<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->string('filepath')->after('foto')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropColumn('filepath');
        });
    }
};