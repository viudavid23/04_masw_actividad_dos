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
        Schema::create('languages', function (Blueprint $table) {
            $table->comment('Store languages information');
            $table->id();
            $table->string('name', 50)->nullable(false);
            $table->string('iso_code', 50)->unique()->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
