<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Remove the old global boolean
        Schema::table('packing_list_items', function (Blueprint $table) {
            $table->dropColumn('is_packed');
        });

        // 2. Create the pivot table for individual tracking
        Schema::create('packing_list_item_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packing_list_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_list_item_user');
        Schema::table('packing_list_items', function (Blueprint $table) {
            $table->boolean('is_packed')->default(false);
        });
    }
};