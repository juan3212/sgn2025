<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documento Legal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    {{-- Renderizamos el componente según el tipo --}}
    @if($tipo == 'contrato')
        <x-matriculas.contrato-educativo :attributes="new \Illuminate\View\ComponentAttributeBag($datos)" />
    @elseif($tipo == 'carta')
        <x-matriculas.carta-instrucciones :attributes="new \Illuminate\View\ComponentAttributeBag($datos)" />
    @elseif($tipo == 'pagare')
        <x-matriculas.pagare :attributes="new \Illuminate\View\ComponentAttributeBag($datos)" />
    @endif
    @if($tipo == 'autorizacion')
        <x-matriculas.autorizacion :attributes="new \Illuminate\View\ComponentAttributeBag($datos)" />
    @endif
</body>
</html>