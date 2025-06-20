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
        Schema::create('content_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title2');
            $table->string('title3');
            $table->string('title4');
            $table->string('title5');
            $table->string('title6');
            $table->string('title7');
            $table->string('title8');
            $table->string('title9');
            $table->string('title10');
            $table->string('title11');
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_categories');
    }
};
