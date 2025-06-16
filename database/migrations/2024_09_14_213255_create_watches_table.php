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
        Schema::create('watches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('customers');  
            $table->unsignedBigInteger('videoId')->unsigned();
            $table->foreign('videoId')->references('id')->on('digital_contents');
            $table->smallInteger('like')->default(0);
            $table->smallInteger('watch')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watches');
    }
};
