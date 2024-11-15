@csrf
<div>
    <div class="flex justify-end items-end">
        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="location.href='companies/add'">
            Add New Company
        </button>
    </div>
    <div class="relative overflow-x-auto md:h-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Id</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Updated at</th>
                    @hasrole('Admin')
                    <th scope="col" class="px-6 py-3"></th>
                    @endhasrole
                </tr>
            </thead>
    
            <tbody>
                @foreach ($companies as $company)
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$company->id}}    
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$company->name}}    
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{\Carbon\Carbon::parse($company->updated_at)->format('F d, Y')}}    
                    </th>
                    @hasrole('Admin')
                    <td class="px-6 py-4"> <a href="{{route('companies.edit', $company->id)}}">Edit</a> </td>
                    @endhasrole                    
                </tr>
                @endforeach   
            </tbody>
        </table> 
        {{$companies->links()}}         
    </div>
</div>
