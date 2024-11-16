<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data add') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="sm:max-w-lg w-full p-10 bg-white rounded-xl z-10 mx-auto">
            <div class="text-center">
                <h2 class="mt-5 text-3xl font-bold text-gray-900">
                    File Data Import
                </h2>
                <p class="mt-2 text-sm text-gray-400">Import data to database</p>
            </div>
            <form action="{{route('sales.import')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                @include('sales._import', ['btnText' => 'Import'])
            </form>
            
        </div>
    </div>
    
</x-app-layout>

