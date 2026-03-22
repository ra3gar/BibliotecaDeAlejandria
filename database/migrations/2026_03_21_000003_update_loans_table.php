<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify status enum to add 'pending'
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending','active','returned','overdue') NOT NULL DEFAULT 'pending'");

        Schema::table('loans', function (Blueprint $table) {
            $table->string('qr_token')->unique()->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });

        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('active','returned','overdue') NOT NULL DEFAULT 'active'");
    }
};
