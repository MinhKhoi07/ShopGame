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
        // Bổ sung các cột phục vụ tính năng chat: is_from_admin, is_read
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'is_from_admin')) {
                $table->boolean('is_from_admin')->default(false)->after('message');
            }
            if (!Schema::hasColumn('messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('is_from_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('messages', 'is_from_admin')) {
                $table->dropColumn('is_from_admin');
            }
        });
    }
};
