<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-xl font-bold mb-4">Comparación de Ventas</h2>
        
        <!-- Mostrar tabla con los resultados de la comparación -->
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
                @foreach ($results as $result)
                    <tr class="{{ $result['status'] == 'updated' ? 'bg-yellow-100' : 'bg-red-100' }}">
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <strong>{{ ucfirst($result['status']) }}</strong>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['date'] }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['user_name'] }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['email'] }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ number_format($result['data']['amount'], 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['product_name'] }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['company_name'] }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $result['data']['award_name'] }}</td>
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
