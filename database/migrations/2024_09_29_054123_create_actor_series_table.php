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
        Schema::create('actor_series', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id', false, true);
            $table->unsignedBigInteger('serie_id', false, true);
            $table->primary(['actor_id', 'serie_id']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('actor_id')->references('id')->on('actors');
            $table->foreign('serie_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actor_series');
    }
};
