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
        Schema::create('docker_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('container_name');
            $table->unsignedBigInteger('image_id');
            $table->foreign('image_id')->references("id")->on("docker_images")->onDelete('cascade');
            $table->string('container_id');
            $table->json('portMap');
            $table->string('status');
            $table->boolean('running');
            $table->boolean('paused');
            $table->boolean('restarting');
            $table->boolean('oom_killed');
            $table->boolean('dead');
            $table->integer('pid');
            $table->integer('exit_code');
            $table->string('error');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docker_containers');
    }
};
