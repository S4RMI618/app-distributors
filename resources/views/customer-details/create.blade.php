<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <form method="POST" action="{{ route('customer-details.store') }}">
                    @csrf

                    <!-- Identificación -->
                    <div>
                        <x-input-label for="identification" :value="__('Identificación')" />
                        <x-text-input id="identification" class="block mt-1 w-full" type="text" name="identification"
                            :value="old('identification')" required autofocus />
                        <x-input-error :messages="$errors->get('identification')" class="mt-2" />
                    </div>

                    <!-- Nombre Completo -->
                    <div class="mt-4">
                        <x-input-label for="full_name" :value="__('Nombre Completo')" />
                        <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name"
                            :value="old('full_name')" required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                            :value="old('phone')" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Dirección')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                            :value="old('address')" required />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Companies -->
                    <div class="mt-4">
                        <x-input-label for="company_id" :value="__('Compañía')" />
                        <select id="company_id" name="company_id"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="">Selecciona una compañía</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center md:justify-end mt-4">
                        <x-primary-button type="submit" class="ml-4">
                            {{ __('Crear Cliente') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
