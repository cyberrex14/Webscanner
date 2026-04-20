<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // 🔗 relasi ke scan
            $table->unsignedBigInteger('scan_id');

            // jenis vulnerability
            $table->string('type'); // XSS, SQLi, Header

            // 🔥 severity level
            $table->enum('severity', ['low', 'medium', 'high']);

            // deskripsi hasil scan
            $table->text('description');

            $table->timestamps();

            // foreign key
            $table->foreign('scan_id')
                  ->references('id')
                  ->on('scans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
