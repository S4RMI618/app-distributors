<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Formulario de edición de usuario -->
                <form method="POST" action="{{ route('customer-details.update', $customerDetail->id) }}">
                    @csrf
                    @method('PUT')
                
                    <!-- Identificación -->
                    <div>
                        <x-input-label for="identification" :value="__('Identificación')" />
                        <x-text-input id="identification" class="block mt-1 w-full" type="text" name="identification" :value="old('identification', $customerDetail->identification)" required autofocus />
                        <x-input-error :messages="$errors->get('identification')" class="mt-2" />
                    </div>

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="full_name" :value="__('Nombre')" />
                        <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name', $customerDetail->full_name)" required autofocus />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>
                
                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $customerDetail->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                
                    <!-- Teléfono -->
                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $customerDetail->phone)" required/>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    
                    <!-- Dirección -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Dirección')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $customerDetail->address)" required/>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Compañía -->
                    <div class="mt-4">
                        <x-input-label for="company_id" :value="__('Compañía')" />
                        <select id="company_id" name="company_id" class="block mt-1 w-full rounded-md" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $customerDetail->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>
                
                    <!-- Botón de guardar -->
                    <div class="w-full mt-4 flex justify-center">
                        <x-primary-button type="submit" class="ml-3">
                            {{ __('Guardar cambios') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>