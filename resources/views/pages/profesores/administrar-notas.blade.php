@props([
    $periodoId,
    $materiaId,
])
<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/notas.css') }}">
    <livewire:pages.profesores.notas-periodo :periodoId="$periodoId" :materiaId="$materiaId" />
</x-app-layout>