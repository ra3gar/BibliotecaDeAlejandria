<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_store_users', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('book_store_users', function (Blueprint $table) {
            $table->dropColumn('birth_date');
        });
    }
};
