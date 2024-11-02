<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center text-2xl">
                    {{ __("Hola, bienvenido ") . $user->name . "!"}}

                    <div class="w-full text-center p-4 md:p-8 m-2">
                        <h1 class="text-xl">Aquí se mostrarán las estadísticas de tu empresa.</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
