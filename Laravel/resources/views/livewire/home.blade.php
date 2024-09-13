<div>


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" wire:poll.500ms>
        @foreach ($images as $image)
            <div
                class="border rounded-lg shadow border-gray-400 dark:bg-gray-800 mb-5 ml-5 flex flex-col transform transition-transform duration-500 ease-in-out hover:scale-105 hover:duration-150 hover:shadow-3xl hover:shadow-blue-500/50 ">
                <div>
                    <div class="p-5 flex-grow">
                        <a>
                            <img class="rounded-lg mx-auto p-4 w-30 h-30 object-contain"
                                src="{{ asset($image->image_logo) }}" alt="{{ $image->image_name }}" />
                        </a>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white text-center">
                            {{ $image->image_name }}</h5>
                    </div>
                </div>

                @if ($image->image_status == 1)
                    <div class="flex justify-center mt-auto">
                        <a wire:click="draft_image({{ $image->id }})" wire:target="draft_image({{ $image->id }})"
                            wire:loading.attr="disabled"
                            class="launch-button w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">


                            <div wire:loading wire:target="draft_image({{ $image->id }})">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>


                            <div wire:loading.remove wire:target="draft_image({{ $image->id }})">
                                Launch
                                <svg class="arrow-bounce rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </a>
                    </div>

                @elseif($image->image_status == 0)
                <div class="flex justify-center mt-auto">
                    <a
                        class="launch-button w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">


                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                    </a>
                </div>
                @else
                    <div class="flex justify-center mt-auto">
                        <a wire:click="download_image('{{ $image->image_repo_name }}')"
                            wire:target="download_image('{{ $image->image_repo_name }}')" wire:loading.attr="disabled"
                            class="launch-button w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">


                            <div wire:loading wire:target="download_image('{{ $image->image_repo_name }}')">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>


                            <div wire:loading.remove wire:target="download_image('{{ $image->image_repo_name }}')">
                                Download
                                <svg class="arrow-bounce rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif

            </div>
        @endforeach
    </div>


    <div class="fixed inset-0 flex items-center justify-center z-50" x-data="{ showModal: @entangle('showModal') }" x-show="showModal"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        style="display: none">

        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 backdrop-blur-lg duration-300"></div>

        <div class="dark:bg-gray-800 border-gray-400 rounded-lg shadow-lg p-6 w-full max-w-md relative z-10">
            <h2 class="text-xl font-bold mb-4">Quick Space Name</h2>
            <input type="text" wire:model="spaceName" placeholder="Space Name"
                class="w-full  bg-transparent  p-2  border-b-blue-500 border-b-2  rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
            @error('spaceName')
                <span class="text-red-500">{{ $message }}</span>
            @enderror


            @if (@$clicked_image->image_type == 'OS')
                <h2 class="text-xl font-bold mb-4">Password </h2>
                <input type="password" wire:model="password" placeholder="Space Password"
                    class="w-full  bg-transparent  p-2  border-b-blue-500 border-b-2  rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4">
                @error('password')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            @endif

            <div class="flex justify-end">
                <button wire:click="closeModal" class="px-4 py-2 mr-2 text-white focus:outline-none" wire:loading.remove
                    wire:target="SaveAndStartSpace">
                    Cancel
                </button>
                <button wire:click="SaveAndStartSpace" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none launch-button">

                    <div wire:loading.remove wire:target="SaveAndStartSpace">
                        Create Space
                        <svg class="arrow-bounce rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </div>
                    <div wire:loading wire:target="SaveAndStartSpace">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                </button>
            </div>
        </div>
    </div>




    {{-- <button wire:click="start">click</button> --}}

</div>
