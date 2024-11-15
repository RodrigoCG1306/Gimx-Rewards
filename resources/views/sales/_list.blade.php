<div class="flex justify-center min-h-screen">
    <!-- Contenedor de filtros a la izquierda -->
    <div class="w-1/8 p-4">
        <div class="bg-white p-10 rounded-md mb-4">
            <h2 class="text-lg font-semibold">Filter Sales</h2>
            <form action="{{ route('sales.list') }}" method="GET">
                <div class="mt-4">
                    <label for="user_id" class="block font-medium">User:</label>
                    <select name="user_id" id="user_id" class="border rounded w-full p-2">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @if ($user_id == $user->id) selected @endif>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <label for="company_id" class="block font-medium">Company:</label>
                    <select name="company_id" id="company_id" class="border rounded w-full p-2">
                        <option value="">All Companies</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @if ($company_id == $company->id) selected @endif>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <label for="product_id" class="block font-medium">Product:</label>
                    <select name="product_id" id="product_id" class="border rounded w-full p-2">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if ($product_id == $product->id) selected @endif>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <label for="award_id" class="block font-medium">Award:</label>
                    <select name="award_id" id="award_id" class="border rounded w-full p-2">
                        <option value="">All Awards</option>
                        @foreach ($awards as $award)
                            <option value="{{ $award->id }}" @if ($award_id == $award->id || $current_season_id == $award->id) selected @endif>{{ $award->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium rounded py-2 px-4">
                        Apply Filters
                    </button>
                </div>
                <div class="mt-2">
                    <a href="{{ route('sales.list') }}" class="block text-blue-500 hover:text-blue-600 font-medium">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenedor de la tabla de ventas centrada en la pantalla -->
    <div class="w-7/8 p-4">
            <a href="{{ route('sales.add') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Add New Sale
            </a> <br><br>
        <div class="overflow-x-auto md:h-auto shadow-md sm:rounded-lg">
            <table class="w-full min-w[1024px] text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Sub agent</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-9 py-3">Amount</th>
                        <th scope="col" class="px-6 py-3">Product</th>
                        <th scope="col" class="px-6 py-3">Company</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        @hasrole('Admin')
                            <th scope="col" class="px-6 py-3"></th>
                        @endhasrole
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sales as $sale)
                        <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sale->user->name }}
                            </th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sale->user->email }}
                            </td>
                            <td class="px-9 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ '$ ' . number_format($sale->amount) }} USD
                            </td>
                            <td class="px-9 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sale->product->name }}
                            </td>
                            <td class="px-9 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sale->company->name }}
                            </td>
                            <td class="px-9 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ \Carbon\Carbon::parse($sale->date)->format('F d, Y') }}
                            </td>
                            @hasrole('Admin')
                                <td class="px-6 py-4" style="min-width: 100px;">
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="text-blue-500 hover:text-blue-600 font-medium">
                                        Edit
                                    </a>
                                </td>
                            @endhasrole
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Paginación de la tabla -->
        {{ $sales->appends(['user_id' => $user_id, 'product_id' => $product_id, 'company_id' => $company_id])->links() }}
    </div>
    <div style="clear:both;"></div>
</div>
