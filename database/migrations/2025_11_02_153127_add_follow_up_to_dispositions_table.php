<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            $table->text('follow_up_notes')->nullable()->after('notes');
            $table->string('follow_up_attachment')->nullable()->after('follow_up_notes');
        });
    }

    public function down(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropColumn(['follow_up_notes', 'follow_up_attachment']);
        });
    }
};