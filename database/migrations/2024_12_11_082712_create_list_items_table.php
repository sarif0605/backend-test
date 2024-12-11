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
        Schema::create('list_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('notes_id');
            $table->uuid('parent_id')->nullable();
            $table->text('description');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->foreign('notes_id')->references('id')->on('notes')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('list_items')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_items');
    }
};
