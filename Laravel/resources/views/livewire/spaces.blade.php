<div>
    {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach ($containers as $container)
            <div
                class="border rounded-lg shadow border-gray-400 dark:bg-gray-800 mb-5 ml-5 flex flex-col transform transition-transform duration-500 ease-in-out hover:scale-105 hover:duration-150 hover:shadow-3xl hover:shadow-blue-500/50 ">
                <div>
                    <div class="p-5 flex-grow">
                        <a>
                            <img class="rounded-lg mx-auto p-4 w-30 h-30 object-contain"
                                src="{{ asset($container->image->image_logo) }}"
                                alt="{{ $container->image->image_name }}" />
                        </a>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
                            {{ $container->image->image_name }}</h5>
                    </div>
                </div>
                <div class="flex justify-center mt-auto">
                    <a wire:click="draft_image({{ $container->id }})" wire:target="draft_image({{ $container->id }})"
                        wire:loading.attr="disabled"
                        class="launch-button w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">


                        <div wire:loading wire:target="draft_image({{ $container->id }})">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>

                        <div wire:loading.remove wire:target="draft_image({{ $container->id }})">
                            Launch
                            <svg class="arrow-bounce rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                            </svg>
                        </div>
                    </a>
                </div>

            </div>
        @endforeach
    </div> --}}

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs text-white text-center uppercase bg-gray-50 dark:bg-gray-700 ">
                <tr>

                    <th scope="col" class="px-6 py-3 w-5">#</th>
                    <th scope="col" class="px-6 py-3"></th>
                    <th scope="col" class="px-6 py-3">Space</th>
                    <th scope="col" class="px-6 py-3">Image</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Services</th>
                    <th scope="col" class="px-6 py-3 ">Operations</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($containers as $container)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900  hover:text-white">

                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->index + 1 }} </th>

                        <td class="">
                            <img class="rounded-lg mx-auto p-4 w-20 h-20 object-contain"
                                src="{{ asset($container->image->image_logo) }}"
                                alt="{{ $container->image->image_name }}" />
                        </td>

                        <td class="">{{ $container->container_name }}</td>
                        <td class="">{{ $container->image->image_name }}</td>
                        <td class="uppercase">
                            <span>

                                @if ($container->paused)
                                    <span wire:loading wire:target="restartContainer('{{ $container->container_id }}')">STARTING...</span>
                                    <span wire:loading.remove wire:target="restartContainer('{{ $container->container_id }}')">PAUSED</span>
                                @elseif ($container->running)
                                    <span wire:loading wire:target="pauseContainer('{{ $container->container_id }}')">PAUSING...</span>
                                    <span wire:loading.remove wire:target="pauseContainer('{{ $container->container_id }}')">RUNNING</span>
                                @endif
                            </span>
                        </td>

                        <td class=" py-4">
                            <ul class="list-disc list-inside">

                                @foreach ($container->portMap as $port)
                                    <a href="@if (@$container->image->image_type == 'OS'){{ 'https' }}@else{{ 'http' }}@endif://localhost:{{ $port }}"
                                        target="_blank">
                                        <li class="hover:text-green-500"> {{ $port }}</li>
                                        {{-- <button
                                        class=" w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"></button> --}}
                                    </a>
                                @endforeach

                        <td class="py-4 justify-center">

                            <div class="flex gap-5 text-center justify-evenly">

                                {{-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 cursor-pointer">
                                    <path fill-rule="evenodd"
                                        d="M15.75 4.5a3 3 0 1 1 .825 2.066l-8.421 4.679a3.002 3.002 0 0 1 0 1.51l8.421 4.679a3 3 0 1 1-.729 1.31l-8.421-4.678a3 3 0 1 1 0-4.132l8.421-4.679a3 3 0 0 1-.096-.755Z"
                                        clip-rule="evenodd" />
                                </svg> --}}

                                <div class="relative">
                                    @if ($container->paused)
                                        {{-- Restart Container Button --}}

                                        <span wire:loading
                                            wire:target="restartContainer('{{ $container->container_id }}')"
                                            style="absolute"></span>

                                        <div wire:loading.remove
                                            wire:target="restartContainer('{{ $container->container_id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                wire:click="restartContainer('{{ $container->container_id }}')"
                                                stroke-width="1.5" stroke="currentColor" class="size-6 cursor-pointer">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                        </div>

                                        {{-- Loading Spinner for Restart --}}

                                        <div wire:loading
                                            wire:target="restartContainer('{{ $container->container_id }}')"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <svg class="animate-spin h-5 w-5 text-blue-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.292a7.953 7.953 0 0 1-2-5.292H0c0 2.904 1.236 5.51 3.214 7.288l2.786-2.996z">
                                                </path>
                                            </svg>
                                        </div>
                                    @elseif($container->running)
                                        {{-- Pause Container Button --}}
                                        <div wire:loading.remove
                                            wire:target="pauseContainer('{{ $container->container_id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                wire:click="pauseContainer('{{ $container->container_id }}')"
                                                stroke-width="1.5" stroke="currentColor" class="size-6 cursor-pointer">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                                            </svg>
                                        </div>

                                        {{-- Loading Spinner for Pause --}}

                                        <div wire:loading
                                            wire:target="pauseContainer('{{ $container->container_id }}')"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <svg class="animate-spin h-5 w-5 text-blue-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.292a7.953 7.953 0 0 1-2-5.292H0c0 2.904 1.236 5.51 3.214 7.288l2.786-2.996z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>



                                {{-- Stop Button --}}
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        wire:click="stopContainer('{{ $container->container_id }}')"
                                        wire:loading.remove wire:loading.class="opacity-50 cursor-not-allowed"
                                        wire:target="stopContainer('{{ $container->container_id }}')"
                                        class="size-6 cursor-pointer">
                                        <path fill-rule="evenodd"
                                            d="M4.5 7.5a3 3 0 0 1 3-3h9a3 3 0 0 1 3 3v9a3 3 0 0 1-3 3h-9a3 3 0 0 1-3-3v-9Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{-- Loading Spinner --}}
                                    <div wire:loading wire:target="stopContainer('{{ $container->container_id }}')"
                                        class="absolute inset-0 flex items-center justify-center">
                                        <svg class="animate-spin h-5 w-5 text-blue-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.292a7.953 7.953 0 0 1-2-5.292H0c0 2.904 1.236 5.51 3.214 7.288l2.786-2.996z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                            </div>

                        </td>

                        </ul>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>


</div>
