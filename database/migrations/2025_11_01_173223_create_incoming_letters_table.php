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
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('agenda_number')->unique(); // No. Agenda (cth: 001/XI/2025)
            $table->string('tracking_code')->unique(); // Kode unik untuk pelacakan publik
            
            $table->string('sender_name'); // Dari / Nama Pengirim
            $table->text('sender_address')->nullable(); // Alamat Pengirim
            
            $table->string('letter_number')->nullable(); // No. Surat (dari pengirim)
            $table->date('letter_date'); // Tgl. Surat
            $table->timestamp('received_at'); // Tgl. Masuk / Diterima
            
            $table->string('subject'); // Perihal
            $table->string('trait')->default('Biasa'); // Sifat (Biasa, Penting, Segera)
            $table->string('category'); // Kategori (Permohonan Bantuan, Undangan, dll)
            
            $table->string('file_path')->nullable(); // Path ke file PDF/scan surat
            $table->string('status')->default('Diterima Petugas'); // Status untuk tracking
            
            $table->foreignId('created_by_user_id')->constrained('users'); // Petugas yg input
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};