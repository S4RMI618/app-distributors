<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Orden') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <!-- Sección de encabezado y botón -->
                    <div class="flex justify-center md:justify-normal items-center mb-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ __('Información de la Orden') }}
                        </h3>
                    </div>

                    <!-- Sección de Información de la Orden -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <p><strong>ID de la Orden:</strong> {{ $order->id }}</p>
                        <p><strong>Cliente:</strong> {{ $order->customer ? $order->customer->full_name : 'No asignado' }}</p>
                        <p><strong>Subtotal:</strong> ${{ number_format($order->subtotal, 2) }}</p>
                        <p><strong>Impuestos:</strong> ${{ number_format($order->total_tax, 2) }}</p>
                        <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                        <p><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
                        <p><strong>Usuario:</strong> {{ $order->user->name }}</p>
                    </div>
                    <!-- Sección de observaciones -->
                    <div class="w-full rounded-sm my-4 border-gray-200 border-t-2 border-b-2 py-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center md:text-left">
                            {{ __('Observaciones') }}
                        </h3>
                        <p>
                            @if ($order->observations)
                                {{ $order->observations }}
                            @else
                                <p class="text-center md:text-left">{{ __('No hay observaciones para mostrar!') }}</p>
                            @endif
                        </p>
                    </div>

                    <!-- Título de Productos de la Orden -->
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center md:text-left">
                        {{ __('Productos de la Orden') }}
                    </h3>

                    <!-- Tabla de Productos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Precio Unitario
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Cantidad
                                    </th>
                                    {{-- <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Subtotal
                                    </th> --}}
                                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Impuestos
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($order->products as $product)
                                    <tr>
                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $product->name }} ({{ $product->code }})
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                                            ${{ number_format(($product->pivot->price_final ), 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                                            {{ $product->pivot->quantity }}
                                        </td>
                                        {{-- <td class="px-4 py-4 text-sm text-gray-900 text-center">
                                            ${{ number_format($product->pivot->subtotal, 2) }}
                                        </td> --}}
                                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                                            ${{ number_format($product->pivot->total_tax, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 text-center">
                                            ${{ number_format($product->pivot->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-center gap-4 mt-6">
                        <a href="{{ route('orders.index') }}" class="text-white bg-blue-600 hover:bg-blue-900 hover:scale-110 duration-300 ease-in-out p-1 px-3 rounded-md">
                            Volver a Órdenes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
