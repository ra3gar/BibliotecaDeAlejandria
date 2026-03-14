<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('stock_total')->default(0)->after('published_at');
            $table->unsignedInteger('available_copies')->default(0)->after('stock_total');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['stock_total', 'available_copies']);
        });
    }
};
