@csrf
<div class="w-full max-w-sm md:h-fit p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
    
    <form method="POST" action="{{ route('register') }}">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Sub Agent')" />
            <select name="user" id="user" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                <option value=""></option>
                @foreach ($users as $user)
                    <option value="{{$user->id}}" @selected($user->id == $sale->user_id)>{{$user->name}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Amount -->
        <div class="mt-4">
            <x-input-label for="amount" :value="__('Amount')" />
            <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" value="{{$sale->amount}}"/>
            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        </div>

        <!-- Product -->
        <div class="mt-4">
            <x-input-label for="product" :value="__('Product')" />
            <select name="product" id="product" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                @foreach ($products as $product)
                    <option value="{{$product->id}}" @selected($product->id == $sale->product_id)>{{$product->name}}</option>
                @endforeach
            </select>
        </div>

        <!-- Company -->
        <div class="mt-4">
            <x-input-label for="company" :value="__('Company')" />
            <select name="company" id="company" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                @foreach ($companies as $company)
                    <option value="{{$company->id}}" @selected($company->id == $sale->company_id)>{{$company->name}}</option>
                @endforeach
            </select>
        </div>

        <!-- Date -->
        <div class="mt-4">
            <x-input-label for="date" :value="__('Date')" />
            <input type="date" name="date" id="date" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" value="{{$sale->date}}">
        </div>

        <!-- Award -->
        <div class="mt-4">
            <x-input-label for="award" :value="__('award')" />
            <select name="award" id="award" class="px-4 py-3 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                @foreach ($awards as $award)
                    <option value="{{$award->id}}" @selected($award->id == $sale->award_id)>{{$award->name}}</option>
                @endforeach
            </select>
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