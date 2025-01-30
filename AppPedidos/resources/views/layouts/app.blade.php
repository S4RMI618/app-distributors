<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Distri-APP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @if (app()->environment('local'))
        <!-- Development -->
        <script src="https://cdn.tailwindcss.com"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Production -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-?.css') }}">
        <script src="{{ asset('build/assets/app-?.js') }}" defer></script>
    @endif
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="flex justify-between max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                    <button onclick="goBack()" class="btn btn-secondary">Volver</button>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="">
            {{ $slot }}
        </main>
    </div>

    <!-- Notification Modal -->
    <x-modal name="notification-modal" :show="false" maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium" x-text="modalTitle"
                :class="{
                    'text-green-600': modalType === 'success',
                    'text-red-600': modalType === 'error',
                    'text-blue-600': modalType === 'info'
                }">
            </h2>
            <p class="mt-1 text-sm text-gray-600" x-text="modalMessage"></p>
            <div class="mt-6 flex justify-end">
                <x-primary-button @click="show = false">Cerrar</x-primary-button>
            </div>
        </div>
    </x-modal>

    {{-- @livewireScripts --}}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('modalData', {
                type: '', // Inicializa valores por defecto
                title: '',
                message: ''
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            window.showNotification = function(type, title, message) {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'notification-modal'
                }));
                Alpine.store('modalData', {
                    type: type,
                    title: title,
                    message: message
                });
            }
        });
    </script>


    <!-- Stack for additional scripts -->
    @stack('scripts')

    <!-- Notification trigger -->
    @if (session('notification'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification(
                    "{{ session('notification.type') }}",
                    "{{ session('notification.title') }}",
                    "{{ session('notification.message') }}"
                );
            });
        </script>
    @endif
</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>

</html>
