<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id();

            // 🔗 relasi ke user
            $table->unsignedBigInteger('user_id');

            // 🔥 target scan
            $table->string('target_url');

            // status scan
            $table->enum('status', ['pending', 'scanning', 'done', 'failed'])
                  ->default('pending');

            // tracking waktu
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            // foreign key (optional kalau mau)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
