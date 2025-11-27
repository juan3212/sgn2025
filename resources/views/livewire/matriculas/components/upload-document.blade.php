<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">{{ ucfirst($tipoDocumento) }}</legend>
        <div>
            <label for="documento_pdf" class="block text-sm font-medium text-gray-700">Subir documento</label>
            <input type="file" @if(!$documentoUrl) required @endif  name="documento_pdf" id="documento_pdf"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                wire:model="documento"
                accept=".pdf">
        </div>
        <div>
            @if($documentoUrl)
            <a href="{{ route("documentos.show", [$documentoUrl, time()]) }}" target="_blank" class="text-blue-500 hover:underline">Ver documento</a>
            @endif
        </div>
    </fieldset>
</div>