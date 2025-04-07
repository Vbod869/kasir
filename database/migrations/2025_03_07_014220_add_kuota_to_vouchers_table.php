<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('kuota')->default(1)->after('diskon'); // Kuota default 1
        });
    }

    public function down() {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('kuota');
        });
    }
};
