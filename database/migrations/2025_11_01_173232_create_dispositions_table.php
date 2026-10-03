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
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incoming_letter_id')->constrained('incoming_letters')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users'); // Pimpinan yg disposisi
            $table->foreignId('to_user_id')->constrained('users'); // Petugas yg ditugaskan
            
            $table->text('notes'); // Catatan / Instruksi
            $table->string('status')->default('Pending'); // Status tugas (Pending, Selesai)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};