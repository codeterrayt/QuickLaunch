<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Test extends Component
{

    public function start(){
        // $response = Http::get('http://localhost:3000');

        // $response = Http::post('http://localhost:3000', [

        //         'ports' => [6901],
        //         'image' => 'kasmweb/core-ubuntu-focal:1.14.0'

        // ]);

        $response = Http::post(env("NODE_JS_SERVER")."/start", [
                'ports' => [8042,8088 ,19888 ,50070 ,50075 ],
                'image' => 'harisekhon/hadoop'
        ]);


        dd($response->json());
    }

    public function render()
    {
        return view('livewire.test');
    }
}
