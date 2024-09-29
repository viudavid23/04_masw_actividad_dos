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
        Schema::create('platform_series', function (Blueprint $table) {
            $table->unsignedBigInteger('platform_id', false, true);
            $table->unsignedBigInteger('serie_id', false, true);
            $table->primary(['platform_id', 'serie_id']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->foreign('serie_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_series');
    }
};
