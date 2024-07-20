<?php

namespace App\Livewire;

use App\Models\DockerImage;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\DockerContainer;

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


        $response = Http::post(env("NODE_JS_SERVER")."/spawn/container", $options);


        $container = DockerContainer::create([
            'user_id' => auth()->id(),
            'container_id' => $response['container_id'],
            'portMap' => $response['portMap'],
            'status' =>  $response['container_state']['Status'],
            'running' =>  $response['container_state']['Running'],
            'paused' => $response['container_state']['Paused'],
            'restarting' => $response['container_state']['Restarting'],
            'oom_killed' =>  $response['container_state']['OOMKilled'],
            'dead' =>  $response['container_state']['Dead'],
            'pid' => $response['container_state']['Pid'],
            'exit_code' => $response['container_state']['ExitCode'],
            'error' => $response['container_state']['Error'],
        ]);
        dd($response->json());

    }

    public function render()
    {
        return view('livewire.test');
    }
}
