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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 8, 2)->nullable();      // 1.5, 200, null for "to taste"
            $table->string('unit', 50)->nullable();             // cup, g, ml, tbsp — null for "to taste"
            $table->string('name', 255);                        // flour, butter, eggs
            $table->unsignedSmallInteger('order')->default(0);  // preserve display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
