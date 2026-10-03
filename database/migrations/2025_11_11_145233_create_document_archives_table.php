<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_archives', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Dokumen (cth: "Daftar Hadir Rapat 20 Nov")
            $table->text('description')->nullable(); // Keterangan
            $table->string('file_path'); // Path ke file PDF/JPG/PNG
            $table->date('document_date'); // Tanggal dokumen dibuat
            $table->foreignId('uploaded_by_user_id')->constrained('users'); // Relasi ke tabel 'users'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_archives');
    }
};