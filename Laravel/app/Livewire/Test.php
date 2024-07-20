<?php

namespace App\Livewire;

use App\Models\DockerImage;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Test extends Component
{

    public function start(){
        $image = DockerImage::first();
        $ports = explode(",",$image->image_expose_port);

        $options = [
            'ports' => $ports,
            'image' => $image->image_repo_name
        ];

        // dd($options);

        $response = Http::post(env("NODE_JS_SERVER")."/start", $options);


        dd($response->json());
    }

    public function render()
    {
        return view('livewire.test');
    }
}
