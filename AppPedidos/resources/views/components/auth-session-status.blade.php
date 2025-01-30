@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-green-600 text-2xl']) }}>
        {{ $status }}
    </div>
@endif
