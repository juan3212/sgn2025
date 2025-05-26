<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Competencias Materia') }}
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link  href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">
        </h2>
    </x-slot>
    <div class="py-12">
    @livewire('pages.profesores.materias-competencias', ['materia'=>$materia])
    </div>
</x-app-layout>

 

