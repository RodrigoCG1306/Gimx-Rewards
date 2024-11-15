@section('title', 'Edit Department - GIMX')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($company->name) }}
        </h2>
    </x-slot>

    <section class="h-screen">
        <div class="mx-auto flex lg:justify-center h-full flex-col lg:flex-row">
            <form class="form w-full lg:w-1/2 flex justify-center dark:bg-gray-900 py-5" action="{{ route('companies.update', $company->id) }}" method="POST">
                @method('PUT')
                
                @include('companies._form', ['btnText' => 'Update'])
            </form>
        </div>
    </section>
</x-app-layout>
