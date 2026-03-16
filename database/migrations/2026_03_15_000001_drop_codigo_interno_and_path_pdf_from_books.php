<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['codigo_interno', 'path_pdf']);
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('codigo_interno', 50)->nullable()->unique()->after('isbn');
            $table->string('path_pdf', 500)->nullable()->after('book_cover');
        });
    }
};
