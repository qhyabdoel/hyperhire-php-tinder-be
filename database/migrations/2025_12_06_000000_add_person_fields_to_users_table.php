<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('age')->nullable()->after('password');
            $table->json('pictures')->nullable()->after('age');
            $table->json('location')->nullable()->after('pictures');
            $table->unsignedInteger('like_count')->default(0)->after('location');
            $table->boolean('notified')->default(false)->after('like_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'pictures', 'location', 'like_count', 'notified']);
        });
    }
};
