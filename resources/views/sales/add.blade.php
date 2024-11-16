<x-app-layout>
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-4xl flex justify-between p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
            <form method="POST" action="{{ route('sales.store') }}" class="w-full flex justify-between">
                @csrf
                <!-- Campos no editables (lado izquierdo) -->
                <div class="w-1/3 p-4">
                    <!-- Sub Agent (No editable) -->
                    <div class="mb-4">
                        <x-input-label for="user" :value="__('Sub Agent')" />
                        <div class="px-4 py-3 border-gray-300 focus:ring-2 focus:ring-indigo-200 rounded-md shadow-sm w-full bg-white">
                            {{ auth()->user()->name }}
                        </div>
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    </div>

                    <!-- Email del Usuario (No editable) -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="px-4 py-3 border-gray-300 focus:ring-2 focus:ring-indigo-200 rounded-md shadow-sm w-full bg-white">
                            {{ auth()->user()->email }}
                        </div>
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                    </div>

                    <!-- Current Award (No editable) -->
                    <div class="mb-4">
                        <x-input-label for="award" :value="__('Current Award')" />
                        <div class="px-4 py-3 border-gray-300 focus:ring-2 focus:ring-indigo-200 rounded-md shadow-sm w-full bg-white">
                            {{ $currentSeasonName }} <!-- Muestra el nombre de la temporada actual -->
                        </div>
                        <input type="hidden" name="award_id" value="{{ $currentSeasonId }}">
                    </div>

                    <!-- Date (No editable) -->
                    <div class="mt-4">
                        <x-input-label for="date" :value="__('Date')" />
                        <div class="px-4 py-3 border-gray-300 focus:ring-2 focus:ring-indigo-200 rounded-md shadow-sm w-full bg-white">
                            {{ \Carbon\Carbon::now()->format('d-m-Y') }} <!-- Fecha actual -->
                        </div>
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::now()->toDateString() }}">
                    </div>
                </div>

                <!-- Campos editables (lado derecho) -->
                <div class="w-2/3 p-4">
                    <!-- Amount -->
                    <div class="mt-4">
                        <x-input-label for="amount" :value="__('Amount')" />
                        <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" required />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <!-- Product -->
                    <div class="mt-4">
                        <x-input-label for="product" :value="__('Product')" />
                        <select name="product_id" id="product" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Company -->
                    <div class="mt-4">
                        <x-input-label for="company" :value="__('Company')" />
                        <select name="company_id" id="company" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-between items-end mt-4">
                        <div class="py-4">
                            <button type="submit" class="my-5 w-full flex justify-center bg-blue-500 text-gray-100 p-3 rounded-full tracking-wide font-semibold focus:outline-none focus:shadow-outline hover:bg-blue-600 shadow-lg cursor-pointer transition ease-in duration-300">
                                {{ __('Submit') }}
                            </button>
                        </div>

                        <div class="py-2">
                            <a class="underline text-align-bottom text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard.subagents') }}">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
