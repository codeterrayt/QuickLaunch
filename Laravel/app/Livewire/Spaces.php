<?php

namespace App\Livewire;

use App\Models\DockerContainer;
use Livewire\Component;

class Spaces extends Component
{
    public $containers;

    public function mount(){
        $this->containers = DockerContainer::where("user_id",auth()->id())->with("image")->get();
        // dd($this->containers);
    }

    public function render()
    {
        return view('livewire.spaces');
    }
}
