<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->string("image_type")->nullable();
            $table->integer("image_status")->default(-1);
            $table->timestamps();
        });

        DB::table('docker_images')->insert([
            ['image_name' => 'Ubuntu', 'image_repo_name' => 'kasmweb/core-ubuntu-focal:1.14.0', 'image_logo' => asset('img/logo/hadoop.png'),'image_expose_port'=>'6901','image_type'=>'OS','image_status'=>-1],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docker_images');
    }
};
