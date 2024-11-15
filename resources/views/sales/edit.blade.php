@section('title', 'Edit Department - GIMX')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit a Sale') }}
        </h2>
    </x-slot>

    <section class="h-screen">
        <div class="mx-auto flex lg:justify-center h-full flex-col lg:flex-row">
            <form class="form w-full lg:w-1/2 flex justify-center dark:bg-gray-900 py-5" action="{{ route('sales.update', $sale->id) }}" method="POST">
                @method('PUT')
                
                @include('sales._form', ['btnText' => 'Update'])
            </form>
        </div>
    </section>
</x-app-layout>
