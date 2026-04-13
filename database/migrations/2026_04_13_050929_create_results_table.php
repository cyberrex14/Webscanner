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
        Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('scan_id');
        $table->string('type');
        $table->boolean('is_vulnerable');
        $table->text('payload');
        $table->timestamps();

        $table->foreign('scan_id')
              ->references('id')
              ->on('scans')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
