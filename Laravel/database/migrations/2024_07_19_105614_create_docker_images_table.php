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
        Schema::create('docker_images', function (Blueprint $table) {
            $table->id();
            $table->string("image_name")->nullable();
            $table->string("image_repo_name")->nullable();
            $table->string("image_logo")->nullable();
            $table->string("image_expose_port")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docker_images');
    }
};
