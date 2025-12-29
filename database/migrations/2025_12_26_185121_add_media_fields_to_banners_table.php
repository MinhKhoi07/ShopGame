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
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'media_type')) {
                $table->string('media_type')->default('image')->after('image');
            }
            if (!Schema::hasColumn('banners', 'video_path')) {
                $table->string('video_path')->nullable()->after('image');
            }
            if (!Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('link');
            }
            if (!Schema::hasColumn('banners', 'order')) {
                $table->integer('order')->default(0)->after('link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'video_path', 'is_active', 'order']);
        });
    }
};
