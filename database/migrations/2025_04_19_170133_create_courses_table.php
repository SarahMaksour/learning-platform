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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->string('image')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->decimal('price');
            $table->text('description');
            $table->timestamps();

        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
