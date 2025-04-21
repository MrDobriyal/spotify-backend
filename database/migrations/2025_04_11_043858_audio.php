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
        Schema::create('audio', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('category', [
                'hiphop',
                'pop',
                'rock',
                'classical',
                'jazz',
                'electronic',
                'indian',
                'metal',
                'blues',
                'reggae',
                'folk',
                'country',
                'rnb',
                'techno'
            ]);
            $table->string('duration')->nullable();
            $table->boolean('publish')->default(false);
            $table->unsignedBigInteger('total_listen');
            $table->string('publishedTime')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio');
    }
};
