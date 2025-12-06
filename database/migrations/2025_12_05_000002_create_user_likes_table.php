<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_liked')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_likes');
    }
};
