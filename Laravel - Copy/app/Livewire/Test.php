<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Test extends Component
{

    public function start(){
        // $response = Http::get('http://localhost:3000');

        $response = Http::post('http://localhost:3000', [
            'json' => [
                'ports' => [6901],
                'image' => 'kasmweb/core-ubuntu-focal:1.14.0'
            ]
        ]);

        dd($response->json());
    }

    public function render()
    {
        return view('livewire.test');
    }
}
