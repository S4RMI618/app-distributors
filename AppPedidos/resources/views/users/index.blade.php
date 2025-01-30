<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Lista de Usuarios
                    </h3>
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium text-sm">Crear
                        Nuevo Usuario</a>
                </div>
                <div class="border-t border-gray-200 p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Rol</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        Nombre</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        Email</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Empresa</th>
                                    <th scope="col"
                                        class="mx-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-100 cursor-pointer"
                                        onclick="window.location='{{ route('users.show', $user->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $user->role->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                            {{ $user->company->name }}</td>

                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2 justify-center gap-6">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="text-indigo-600 hover:bg-indigo-900">Editar</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
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
