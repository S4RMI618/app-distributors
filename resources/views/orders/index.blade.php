<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Órdenes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __('Lista de Órdenes') }}
                    </h3>
                    <a href="{{ route('orders.create') }}"
                        class="bg-blue-500 hover:bg-blue-800 text-white px-4 py-2 rounded font-medium text-sm">Crear
                        Nueva Orden</a>

                </div>

                <div class="border-t border-gray-200 p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Orden</th>
                                    @if (Auth::user()->role->name === 'Admin')
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Creador
                                        </th>
                                    @endif
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente</th> <!-- Nueva columna para Cliente -->
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal</th>
                                    {{-- <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Impuestos</th> --}}
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-100 cursor-pointer"
                                        onclick="window.location='{{ route('orders.show', $order) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $order->id }}
                                        </td>
                                        @if (Auth::user()->role->name === 'Admin')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $order->user->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $order->customer ? $order->customer->full_name : 'No asignado' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            ${{ number_format($order->subtotal, 2) }}
                                        </td>
                                        {{-- IMPUESTOS --}}
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            ${{ number_format($order->total_tax, 2) }} 
                                        </td> --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            ${{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ ucfirst($order->status) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center gap-2">

                                            <a href="{{ route('orders.edit', $order->id) }}"
                                                class="text-blue-600 hover:text-blue-900">Editar</a>
                                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="p-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
