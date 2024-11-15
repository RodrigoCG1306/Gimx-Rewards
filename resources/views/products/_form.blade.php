@csrf

<div class="w-full max-w-sm md:h-fit p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
    
    <form method="POST" action="{{ route('register') }}">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" value="{{$product->name}}"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Description -->
        <div class="mt-4">
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" value="{{$product->description}}"/>
        </div>

        <!-- Submit -->
        <div class="flex justify-between items-end mt-4">

            <div class="py-4">
                <button type="submit" class="flex-auto w-64 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 focus:bg-indigo-800 focus:outline-none w-full sm:w-auto bg-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 rounded text-white px-8 py-3 text-sm mt-6">{{ $btnText }}
            </button>
            </div>

            <div class="py-2">
                <a class="underline text-align-bottom text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard.index') }}">
                {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </form>
</div>