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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            
            // Kolom dari RequestForm.jsx
            $table->string('requestType'); // 'kesehatan', 'pendidikan', dll.
            $table->decimal('amount', 15, 2);
            $table->text('reason');
            $table->string('urgency')->default('normal'); // 'normal', 'urgent', 'critical'
            $table->integer('familyMembers');
            $table->decimal('monthlyIncome', 15, 2);
            $table->string('jobStatus'); // 'employed', 'unemployed', dll.
            $table->text('description')->nullable(); // Keterangan tambahan (opsional)

            // Kolom untuk review petugas (dari RequestList.jsx)
            $table->timestamp('reviewedAt')->nullable();
            $table->text('reviewComments')->nullable();

            $table->timestamps(); // submittedAt akan otomatis terisi oleh created_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
