<?php

namespace App\Livewire;

use App\Models\DockerContainer;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Spaces extends Component
{
    public $containers;

    public function mount(){
        $this->loadContainers();
    }

    // A method to load containers, ensuring it's fresh each time it's called
    public function loadContainers()
    {
        $this->containers = DockerContainer::where('user_id', auth()->id())->with('image')->get();
    }

    public function pauseContainer($container_id){

        $container = DockerContainer::with("image")->where("container_id",$container_id)->where("user_id",auth()->id())->first();

        if($container){
            $response = Http::post(env("NODE_JS_SERVER")."/pause/container", ["container_id"=>$container_id, "ports"=>explode(",",$container->image->image_expose_port)]);
            $data = $response->json();

            if($data['success']){
                $container->paused = true;
                $container->status = "Paused";
                $container->restarting = false;
                $container->portMap = $data['portMap'];
                $container->save();

                $this->loadContainers();

            }
        }
    }

    public function restartContainer($container_id){
        $container = DockerContainer::where("container_id",$container_id)->where("user_id",auth()->id())->first();

        if($container){
            $response = Http::post(env("NODE_JS_SERVER")."/start/container", ["container_id"=>$container_id, "ports"=>explode(",",$container->image->image_expose_port)]);
            $data = $response->json();

            // dd($data);

            if($data['success']){
                $container->paused = false;
                $container->status = "Running";
                $container->restarting = true;
                $container->portMap = $data['portMap'];
                $container->save();

                $this->loadContainers();

            }
        }
    }

    public function stopContainer($container_id){
        $container = DockerContainer::where("container_id",$container_id)->where("user_id",auth()->id())->first();

        if($container){
            $response = Http::post(env("NODE_JS_SERVER")."/stop/container", ["container_id"=>$container_id]);
            $data = $response->json();
            // dd($data);
            $container->delete();
            $this->loadContainers();
        }


    }

    public function render()
    {
        return view('livewire.spaces',['containers'=>$this->containers]);
    }
}
