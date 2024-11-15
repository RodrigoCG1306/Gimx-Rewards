<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products')}}
        </h2>
    </x-slot>

    <section>
        @if (session('success'))
         <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
             <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
             <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
             </svg>
             <span class="sr-only">Info</span>
             <div>
             <span class="font-medium">Info alert!</span> New Product succesfully added
             </div>
         </div>
        @endif
     </section>

    <section class="h-screen">
        <div class="mx-auto flex lg:justify-center h-full flex-col lg:flex-row">
            <form action="{{ route('products.store') }}" class="form w-full lg:w-1/2 flex justify-center dark:bg-gray-900 py-5" method="GET">
                @method('GET')

                @include('products._list')
            </form>
        </div>
    </section>
</x-app-layout>