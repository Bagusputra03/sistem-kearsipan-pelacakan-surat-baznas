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
        Schema::table('requests', function (Blueprint $table) {
            // Menautkan Permohonan Bantuan ke Surat Masuk
            $table->foreignId('incoming_letter_id')
                  ->nullable()
                  ->after('user_id') // Posisi kolom
                  ->constrained('incoming_letters')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['incoming_letter_id']);
            $table->dropColumn('incoming_letter_id');
        });
    }
};