<div>
    <fieldset class="border-2 {{ $errors->has('documento') ? 'border-red-400 bg-red-50/30' : ($documentoUrl ? 'border-green-300' : 'border-gray-300') }} p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">{{ ucfirst($tipoDocumento) }} <span class="text-red-500">*</span></legend>
        <div>
            <label for="documento_pdf_{{ $nombreDocumento }}_{{ $usuario }}" class="block text-sm font-medium text-gray-700">
                Subir documento (PDF, JPG o PNG, máx. 10 MB)
            </label>
            <input type="file" @if(!$documentoUrl) required @endif name="documento_pdf" id="documento_pdf_{{ $nombreDocumento }}_{{ $usuario }}"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                wire:model="documento"
                accept=".pdf,.jpg,.jpeg,.png">
            <div wire:loading wire:target="documento" class="text-sm text-indigo-600 mt-1">Cargando archivo...</div>
            @error('documento') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mt-2">
            @if($documentoUrl)
                <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                    Documento cargado:
                    <a href="{{ route('documentos.show', [$documentoUrl, time()]) }}" target="_blank" class="text-blue-600 hover:underline">Ver documento</a>
                </span>
            @elseif($documento)
                <span class="text-sm text-indigo-700 font-medium">Archivo listo para guardar: {{ $documento->getClientOriginalName() }}</span>
            @else
                <span class="text-sm text-amber-700">Pendiente por subir.</span>
            @endif
        </div>
    </fieldset>
</div>
