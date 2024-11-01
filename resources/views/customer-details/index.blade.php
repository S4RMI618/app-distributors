<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Lista de Clientes
                    </h3>
                    <a href="{{ route('customer-details.create') }}"
                        class="bg-blue-500 hover:bg-blue-800 text-white px-4 py-2 rounded font-medium text-sm">Crear
                        Nuevo Cliente</a>
                </div>
                <div class="border-t border-gray-200 p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Identificación</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        Nombre Completo</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        Email</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Teléfono</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Dirección</th>
                                        <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Compañía</th>   
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($customerDetails as $customerDetail)
                                    <tr class="hover:bg-gray-100 cursor-pointer"
                                        onclick="window.location='{{ route('customer-details.show', $customerDetail->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->identification }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->phone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->address }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $customerDetail->company->name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2 justify-center gap-6">
                                            <a href="{{ route('customer-details.edit', $customerDetail->id) }}"
                                                class="text-indigo-600 hover:bg-indigo-900">Editar</a>
                                            <form action="{{ route('customer-details.destroy', $customerDetail->id) }}" method="POST"
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
            </div>
        </div>
    </div>

</x-app-layout>
