<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkannya untuk menghindari error
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 16)->unique()->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'userType')) {
                $table->enum('userType', ['mustahik', 'officer'])->default('mustahik')->after('nik');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom yang akan dihapus
            $columns = ['phone', 'address', 'nik', 'userType'];

            // Cek setiap kolom sebelum mencoba menghapusnya
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', 'userType')) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

