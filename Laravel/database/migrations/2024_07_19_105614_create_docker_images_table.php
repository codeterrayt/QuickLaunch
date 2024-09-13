<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

        // DB::table('docker_images')->insert([
        //     ['image_name' => 'Ubuntu', 'image_repo_name' => 'kasmweb/core-ubuntu-focal:1.14.0', 'image_logo' => asset('img/logo/hadoop.png'),'image_expose_port'=>'6901','image_type'=>'OS','image_status'=>-1],
        // ]);


        $jsonFilePath = storage_path('default_images/docker_images.json');

        // Read the JSON file
        $jsonData = File::get($jsonFilePath);

        // Decode the JSON data
        $dataArray = json_decode($jsonData, true);

        // Extract the data you want to insert
        $dockerImages = [];
        foreach ($dataArray as $item) {
            if ($item['type'] == 'table' && $item['name'] == 'docker_images') {
                foreach ($item['data'] as $data) {
                    $dockerImages[] = [
                        'id' => $data['id'],
                        'image_name' => $data['image_name'],
                        'image_repo_name' => $data['image_repo_name'],
                        'image_logo' => asset($data['image_logo']),
                        'image_expose_port' => $data['image_expose_port'],
                        'image_type' => $data['image_type'],
                        'image_status' => $data['image_status'],
                        'created_at' => $data['created_at'],
                        'updated_at' => $data['updated_at']
                    ];
                }
            }
        }

        // Insert data into the database
        DB::table('docker_images')->insert($dockerImages);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docker_images');
    }
};
