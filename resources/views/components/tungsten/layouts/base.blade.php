@props([
'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __('filament::layout.direction') ?? 'ltr' }}"
    class="filament antialiased bg-gray-100 js-focus-visible">

<head>
    {{ \Filament\Facades\Filament::renderHook('head.start') }}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? "{$title} - " : null }} {{ config('app.name') }}</title>
    <script src="{{ asset('js/qrcode.js') }}"></script>

    @livewireStyles

    @foreach (\Filament\Facades\Filament::getStyles() as $name => $path)
    @if (Str::of($path)->startsWith(['http://', 'https://']))
    <link rel="stylesheet" href="{{ $path }}" />
    @else
    <link rel="stylesheet" href="{{ route('filament.asset', [
                    'file' => " {$name}.css", ]) }}" />
    @endif
    @endforeach

    <link rel="stylesheet" href="{{ \Filament\Facades\Filament::getThemeUrl() }}" />

</head>

<body @class([ 'bg-gray-100 text-gray-900 filament-body' , 'dark:text-gray-100 dark:bg-gray-900'=>
    config('filament.dark_mode'),
    ])>
    {{ $slot }}

    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    <script src="{{ route('filament.asset', [
            'id' => Filament\get_asset_id('app.js'),
            'file' => 'app.js',
        ]) }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')

    {{ \Filament\Facades\Filament::renderHook('body.end') }}
</body>

</html>