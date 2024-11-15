@section('title', 'New User - Rewards')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Company')}}
        </h2>
    </x-slot>

    <section class="h-screen">
        <div class="mx-auto flex lg:justify-center h-full flex-col lg:flex-row">
            <form action="{{ route('companies.store') }}" class="form w-full lg:w-1/2 flex justify-center dark:bg-gray-900 py-5" method="POST">
                @method('POST')

                @include('companies._form', ['btnText' => 'Save'])
            </form>
        </div>
    </section>
</x-app-layout>