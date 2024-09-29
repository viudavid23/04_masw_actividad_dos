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
        Schema::create('language_series', function (Blueprint $table) {
            $table->unsignedBigInteger('language_id', false, true);
            $table->unsignedBigInteger('serie_id', false, true);
            $table->primary(['language_id', 'serie_id']);
            $table->tinyInteger('audio');
            $table->tinyInteger('subtitle');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('serie_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_series');
    }
};
