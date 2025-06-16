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
        Schema::create('digital_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('fileName')->nullable();
            $table->string('poster')->nullable();
            $table->unsignedBigInteger('categoryId')->unsigned();
            $table->foreign('categoryId')->references('id')->on('content_categories');  
            $table->unsignedBigInteger('languageId')->unsigned();
            $table->foreign('languageId')->references('id')->on('languages');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_contents');
    }
};
