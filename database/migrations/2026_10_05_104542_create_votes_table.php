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
            Schema::create('votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
                $table->tinyInteger('value'); // 1 for upvote, -1 for downvote
                $table->timestamps();
                
                // Ensure a user can only have one active vote per activity
                $table->unique(['user_id', 'activity_id']);
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
