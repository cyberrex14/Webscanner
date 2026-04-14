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
          Schema::create('vulnerabilities', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('result_id');
        $table->string('name'); // XSS, SQLi
        $table->string('severity'); // low, medium, high
        $table->text('description');
        $table->timestamps();

        $table->foreign('result_id')
              ->references('id')
              ->on('results')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
