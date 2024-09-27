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
        Schema::create('actors', function (Blueprint $table) {
            $table->comment('Store actors information');
            $table->id();
            $table->string('stage_name', 50)->nullable(true);
            $table->text('biography', 50);
            $table->text('awards', 50)->nullable(true);;
            $table->decimal('height', 5, 2);
            $table->bigInteger('people_id', false, true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('people_id')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actors');
    }
};
