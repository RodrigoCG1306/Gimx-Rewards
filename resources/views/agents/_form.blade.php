@csrf

<div class="w-full max-w-sm md:h-fit p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">

    <form method="POST" action="{{ route('register') }}">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" value="{{ $user->name }}"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" value="{{$user->email}}"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->

        @if (empty($user->password))
            @include('agents._password')
        @endif

        @hasrole('Admin')
        <div class="flex flex-col mt-8">
            <x-input-label for="role" :value="__('Role')" />
            <div class="flex flex-wrap items-stretch w-full mb-4 relative">
                <select name="role" id="role" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                    <option value=""> --Select a Role-- </option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if ($user->hasRole($role->name)) selected @endif> {{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endhasrole
        <!-- Submit -->
        <div class="flex justify-between items-end mt-4">

            <div class="py-4">
                <button type="submit" class="flex-auto w-64 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 focus:bg-indigo-800 focus:outline-none w-full sm:w-auto bg-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 rounded text-white px-8 py-3 text-sm mt-6">{{ $btnText }}
            </button>
            </div>

            <div class="py-2">
                <a class="underline text-align-bottom text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('agents.list') }}">
                {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </form>
</div>