<?php

namespace App\Livewire;

use App\Models\DockerImage;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\DockerContainer;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Home extends Component
{

    use LivewireAlert;

    public $images;

    public function fetchData(){
        $this->images = DockerImage::get();
    }

    public function mount(){
        $this->fetchData();
    }


    // modal

    public $showModal = false;
    public $spaceName = '';
    public $password = '';
    public $current_image, $clicked_image;

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }


    public function draft_image($image_id){

        $this->current_image = $image_id;
        $this->clicked_image = DockerImage::findOrFail($this->current_image);

        $options = [
            'image_repo_name' => $this->clicked_image->image_repo_name,
        ];

        $response = Http::post(env("NODE_JS_SERVER")."/image/exists/", $options);

        // dd($response['exists'],$options);

        if(@$response['exists']){
            $this->openModal();
        }else{
            $this->alert('error', 'Image Not Found!');
            $this->clicked_image->image_status = -1;
            $this->clicked_image->save();
        }
    }


    public function download_image($image){
        $options = [
            'image_repo_name' => $image,
        ];

        try {
            $img = DockerImage::where("image_repo_name", $image)->first();
            $img->image_status = 0;
            $img->save();
            $response = Http::post(env("NODE_JS_SERVER")."/image/pull/", $options);
            if(@$response['exists']){
                $this->alert('success', $response['message']);
                $img->image_status = 1;
                $img->save();
            }
            else if($response['pulling']){
                $this->alert('success', $response['message']);
            }else{
                $this->alert('error', $response['Something Went Wrong!']);
                $img->image_status = -1;
                $img->save();
            }

        } catch (\Throwable $th) {
            $this->alert('error', $response['Image Not Found!']);
        }


    }


    public function SaveAndStartSpace()
    {
        // Validate and save the space name
        $this->validate([
            'spaceName' => 'required|string|max:255',
            // 'password' => 'required|string|max:255',
            'current_image' => 'required|integer|exists:docker_images,id'
        ]);

        if ($this->clicked_image->image_type === 'OS') {
            $this->validate([
                'password' => 'required|string|max:255',
            ]);
        }else{
            $this->password = "secret_password";
        }

        $ports = explode(",",$this->clicked_image->image_expose_port);

        $options = [
            'ports' => $ports,
            'image' => $this->clicked_image->image_repo_name,
            'password' => $this->password
        ];

        $response = Http::post(env("NODE_JS_SERVER")."/spawn/container", $options);


        $container = DockerContainer::create([
            'user_id' => auth()->id(),
            'container_name' => $this->spaceName,
            'image_id' => $this->clicked_image->id,
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

        $this->reset(["spaceName","password"]);

        $this->closeModal();
    }


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
        return view('livewire.home');
    }
}
