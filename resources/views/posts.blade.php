<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    Hello {{ Auth::user()->name }}

                    <button class="bg-white text-black cursor-pointer rounded-md px-4 py-2  transition"
                        onclick="openModal('postModal')">
                        Create Post
                    </button>
                    @include('components.create_post')
                </div>
            </div>
            <div class=" flex flex-wrap gap-5  mt-3">

                @foreach ($posts as $post)
                    <div class="w-[32%] p-5 border border-white flex items-center justify-between">
                        <h1 class="text-white">{{ $post->post }}</h1>


                        @can('delete-post', $post)
                            
                        <div class="">

                            <form method="post" action="{{ route("post.destroy" , $post) }}">
                                @csrf
                                @method('DELETE')

                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red"
                                        class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>
    </div>


</x-app-layout>
