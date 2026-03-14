<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->year('año')->nullable()->after('published_at');
            $table->string('codigo_interno', 50)->unique()->nullable()->after('isbn');
            $table->string('path_pdf')->nullable()->after('book_cover'); // copia digital, si aplica
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['codigo_interno']);
            $table->dropColumn(['año', 'codigo_interno', 'path_pdf']);
        });
    }
};
