@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @if(session('success'))
        <div class="bg-[#5A7D7C] border border-[#4A6968] text-[#DBE7E4] px-4 py-3 rounded shadow text-sm">
            {{ session('success') }}
        </div>
    @endif
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Tailwind Vite Test</title>
    </head>
    <body class="bg-white min-h-screen">    
        <div class="text-center text-3xl font-bold text-[#5A7D7C] mb-0 mt-0 ml-0">
            Trenerių paieškos sistema
        </div>
        <header class="relative p-4">
        </header>
    </body>
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4">
            {{ $errors->first() }}
        </div>
    @endif
</html>
@endsection
