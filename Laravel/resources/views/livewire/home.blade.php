<div>


    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        @foreach ($images as $image)
            <div
                class="border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 bg-white mb-5 ml-5 flex flex-col transform transition-transform duration-500 ease-in-out hover:scale-105 hover:duration-150 hover:shadow-3xl hover:shadow-blue-500/50 ">
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
                <div class="flex justify-center mt-auto">
                    <a
                        wire:click="launch({{ $image->id }})"
                        class="launch-button w-40 mb-5 inline-flex items-center px-3 py-2 justify-center cursor-pointer text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Launch
                        <svg class="arrow-bounce rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </a>
                </div>

            </div>
        @endforeach
    </div>




    {{-- <button wire:click="start">click</button> --}}

</div>
