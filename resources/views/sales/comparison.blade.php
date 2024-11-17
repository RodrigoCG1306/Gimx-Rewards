<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-xl font-bold mb-4">Comparación de Ventas</h2>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Estatus</th>
                    <th class="border border-gray-300 px-4 py-2">Fecha</th>
                    <th class="border border-gray-300 px-4 py-2">Agente</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Monto</th>
                    <th class="border border-gray-300 px-4 py-2">Producto</th>
                    <th class="border border-gray-300 px-4 py-2">Compañía</th>
                    <th class="border border-gray-300 px-4 py-2">Premio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($comparisonResults as $result)
                <tr class="{{ $result['status'] == 'new' ? 'bg-green-100' : ($result['status'] == 'updated' ? 'bg-yellow-100' : 'bg-red-100') }}">
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <strong>{{ ucfirst($result['status']) }}</strong>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['date'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['agent'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['email'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ number_format($result['excel']['amount'], 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['product'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['company'] }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $result['excel']['award'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            <a href="{{ route('sales.list') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Volver a Lista de Ventas
            </a>
        </div>
    </div>
</x-app-layout>
