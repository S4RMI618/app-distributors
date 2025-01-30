<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Empresa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('companies.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="nit" class="block text-sm font-medium text-gray-700">NIT</label>
                            <input type="text" name="nit" id="nit"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="" />
                            @error('nit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- field for name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" id="name"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="" />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- field for phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input type="text" name="phone" id="phone"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="" />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- field for address --}}
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input type="text" name="address" id="address"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="" />
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        {{-- field for department name --}}
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Departamento</label>
                            <select id="department" name="department"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="">Seleccione un departamento</option>
                                @foreach (json_decode(File::get(resource_path('json/departments_municipalities.json')), true) as $department => $municipalities)
                                    <option value="{{ $department }}">{{ $department }}</option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- field for municipality name --}}
                        <div>
                            <label for="municipality" class="block text-sm font-medium text-gray-700">Municipio</label>
                            <select id="municipality" name="municipality"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="">Selecciona un municipio</option>
                            </select>
                            @error('municipality')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 md:flex md:justify-center">
                        <x-primary-button type="submit"
                            class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full md:w-auto">Crear
                            Empresa</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#department').change(function() {
                var department = $(this).val();
                $('#municipality').empty(); // Limpiar los municipios anteriores

                if (department) {
                    $.ajax({
                        url: "{{ route('get-municipalities') }}",
                        type: "GET",
                        data: {
                            department: department
                        },
                        success: function(data) {
                            $('#municipality').append(
                                '<option value="">Seleccione un municipio</option>');
                            $.each(data, function(key, value) {
                                $('#municipality').append('<option value="' + value +
                                    '">' + value + '</option>');
                            });
                        },
                        error: function() {
                            alert('Error al cargar los municipios.');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
