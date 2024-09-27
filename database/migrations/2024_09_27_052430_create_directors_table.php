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
        Schema::create('directors', function (Blueprint $table) {
            $table->comment('Store directors information');
            $table->id();
            $table->date('beginning_career');
            $table->tinyInteger('active_years');
            $table->text('biography', 50);
            $table->text('awards', 50)->nullable();
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
        Schema::dropIfExists('directors');
    }
};
