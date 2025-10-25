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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('book'); // book, article, video, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('authors')->nullable(); // Array of author names
            $table->string('openlibrary_key')->nullable(); // e.g., /works/OL45883W
            $table->string('cover_url')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('first_publish_year')->nullable();
            $table->json('subjects')->nullable(); // Array of subjects
            $table->string('url')->nullable(); // External link
            $table->boolean('is_favorite')->default(false);
            $table->text('notes')->nullable(); // Student's personal notes
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('type');
            $table->index('is_favorite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
