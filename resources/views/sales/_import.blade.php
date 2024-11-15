<div class="grid grid-cols-1 space-y-2">
    <label class="text-sm font-bold text-gray-500 tracking-wide">Import Document</label>
    <div class="flex items-center justify-center w-full">
        <label class="flex flex-col rounded-lg border-4 border-dashed w-full h-60 p-10 group text-center">
            <div class="h-full w-full text-center flex flex-col items-center justify-center items-center">
                <div class="flex flex-auto max-h-48 w-2/5 mx-auto -mt-10">
                    <img class="has-mask h-36 object-center" src="https://img.freepik.com/free-vector/image-upload-concept-landing-page_52683-27130.jpg?size=338&ext=jpg" alt="freepik image">
                </div>
                <p class="pointer-none text-gray-500">
                    <span class="text-sm">Drag and drop</span> files here <br /> 
                    or select a file from your computer
                </p>
            </div>
            <input type="file" name="file" class="hidden" onchange="updateFileName(this)">
        </label>
    </div>
</div>
<p class="text-sm text-gray-300">
    <span>File type: xls</span> <br>
    <span id="file-name-span"></span>
</p>

<!-- Display errors as alerts -->
@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

<script>
    function updateFileName(input) {
        var fileName = input.files[0].name;
        document.getElementById('file-name-span').textContent = fileName;
    }
</script>
<br>
<div>
    <a href="{{ route('sales.download') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Download template</a>
    
    <button type="submit" class="my-5 w-full flex justify-center bg-blue-500 text-gray-100 p-4  rounded-full tracking-wide font-semibold  focus:outline-none focus:shadow-outline hover:bg-blue-600 shadow-lg cursor-pointer transition ease-in duration-300">
        {{$btnText}}
    </button>
</div>
