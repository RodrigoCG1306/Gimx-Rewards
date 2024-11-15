@csrf
<div>
    <div class="flex justify-end items-end">
        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="location.href='awards/add'">
            Add New Award
        </button>
    </div>
    <div class="relative overflow-x-auto md:h-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Start At</th>
                    <th scope="col" class="px-6 py-3">End At</th>
                    <th scope="col" class="px-6 py-3"></th>                    
                </tr>
            </thead>
    
            <tbody>
                @foreach ($awards as $award)
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" value="{{$award->name}}">
                        {{$award->name}}    
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" value="{{$award->start}}">
                        {{\Carbon\Carbon::parse($award->start)->format('F d, Y')}}   
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" value="{{$award->end}}">
                        {{\Carbon\Carbon::parse($award->end)->format('F d, Y')}}    
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <a href="{{route('awards.edit', $award->id)}}">Edit</a>   
                    </th>
                @endforeach   
            </tbody>
        </table> 
        {{$awards->links()}}         
    </div>
</div>
