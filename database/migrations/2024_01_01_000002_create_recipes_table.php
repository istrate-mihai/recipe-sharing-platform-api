<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category');               // breakfast, pasta, soup, …
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->unsignedSmallInteger('prep_time'); // minutes
            $table->unsignedSmallInteger('cook_time'); // minutes
            $table->json('ingredients');               // [{name, amount}, …]
            $table->json('steps');                     // [string, …]
            $table->string('image')->nullable();       // stored filename
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
