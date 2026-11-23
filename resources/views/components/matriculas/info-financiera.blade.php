@props(['form', 'uploadProperties', 'estudianteId'])

<div>
    <fieldset class="border-2 border-gray-300 p-6 rounded-lg">
        <legend class="text-xl font-semibold text-gray-700 px-2">Información Financiera (Acudiente)</legend>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div @if($form->estado_laboral == 'Independiente' || $form->estado_laboral == 'No trabaja') class="md:col-span-2" @endif>
                <label for="estado_laboral" class="block text-sm font-medium text-gray-700">Estado Laboral <span class="text-red-500">*</span></label>
                <select id="estado_laboral" name="estado_laboral" wire:model.live="form.estado_laboral" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione...</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Independiente">Independiente</option>
                    <option value="No trabaja">No trabaja</option>
                </select>
                @error('form.estado_laboral') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            @if ($form->estado_laboral == 'Empleado')
                <div>
                    <label for="empresa" class="block text-sm font-medium text-gray-700">Empresa que trabaja <span class="text-red-500">*</span></label>
                    <input type="text" name="empresa" id="empresa" wire:model="form.empresa" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('form.empresa') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <fieldset class="border-2 {{ $errors->has('form.certificado_laboral') ? 'border-red-400 bg-red-50/30' : ($form->certificado_laboral_url ? 'border-green-300' : 'border-gray-300') }} p-6 rounded-lg">
                        <legend class="text-lg font-semibold text-gray-700 px-2">Certificado laboral <span class="text-red-500">*</span></legend>
                        <div>
                            <label for="acudiente_certificado_laboral" class="block text-sm font-medium text-gray-700">
                                Subir documento (PDF, JPG o PNG, máx. 10 MB)
                            </label>
                            <input type="file"
                                @if(!$form->certificado_laboral_url) required @endif
                                id="acudiente_certificado_laboral"
                                wire:model="form.certificado_laboral"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div wire:loading wire:target="form.certificado_laboral" class="text-sm text-indigo-600 mt-1">Cargando archivo...</div>
                            @error('form.certificado_laboral') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mt-2">
                            @if($form->certificado_laboral_url)
                                <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                                    Documento cargado:
                                    <a href="{{ route('documentos.show', [$form->certificado_laboral_url, time()]) }}" target="_blank" class="text-blue-600 hover:underline">Ver documento</a>
                                </span>
                            @elseif($form->certificado_laboral)
                                <span class="text-sm text-indigo-700 font-medium">Archivo listo para guardar: {{ $form->certificado_laboral->getClientOriginalName() }}</span>
                            @else
                                <span class="text-sm text-amber-700">Pendiente por subir.</span>
                            @endif
                        </div>
                    </fieldset>
                </div>
            @elseif ($form->estado_laboral == 'Independiente')
                <div>
                    <fieldset class="border-2 {{ $errors->has('form.camara_comercio') ? 'border-red-400 bg-red-50/30' : ($form->camara_comercio_url ? 'border-green-300' : 'border-gray-300') }} p-6 rounded-lg">
                        <legend class="text-lg font-semibold text-gray-700 px-2">Cámara de comercio <span class="text-red-500">*</span></legend>
                        <div>
                            <label for="acudiente_camara_comercio" class="block text-sm font-medium text-gray-700">
                                Subir documento (PDF, JPG o PNG, máx. 10 MB)
                            </label>
                            <input type="file"
                                @if(!$form->camara_comercio_url) required @endif
                                id="acudiente_camara_comercio"
                                wire:model="form.camara_comercio"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div wire:loading wire:target="form.camara_comercio" class="text-sm text-indigo-600 mt-1">Cargando archivo...</div>
                            @error('form.camara_comercio') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mt-2">
                            @if($form->camara_comercio_url)
                                <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                                    Documento cargado:
                                    <a href="{{ route('documentos.show', [$form->camara_comercio_url, time()]) }}" target="_blank" class="text-blue-600 hover:underline">Ver documento</a>
                                </span>
                            @elseif($form->camara_comercio)
                                <span class="text-sm text-indigo-700 font-medium">Archivo listo para guardar: {{ $form->camara_comercio->getClientOriginalName() }}</span>
                            @else
                                <span class="text-sm text-amber-700">Pendiente por subir.</span>
                            @endif
                        </div>
                    </fieldset>
                </div>

                <div>
                    <fieldset class="border-2 {{ $errors->has('form.certificado_ingresos') ? 'border-red-400 bg-red-50/30' : ($form->certificado_ingresos_url ? 'border-green-300' : 'border-gray-300') }} p-6 rounded-lg">
                        <legend class="text-lg font-semibold text-gray-700 px-2">Certificado de ingresos <span class="text-red-500">*</span></legend>
                        <div>
                            <label for="acudiente_certificado_ingresos" class="block text-sm font-medium text-gray-700">
                                Subir documento (PDF, JPG o PNG, máx. 10 MB)
                            </label>
                            <input type="file"
                                @if(!$form->certificado_ingresos_url) required @endif
                                id="acudiente_certificado_ingresos"
                                wire:model="form.certificado_ingresos"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div wire:loading wire:target="form.certificado_ingresos" class="text-sm text-indigo-600 mt-1">Cargando archivo...</div>
                            @error('form.certificado_ingresos') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mt-2">
                            @if($form->certificado_ingresos_url)
                                <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                                    Documento cargado:
                                    <a href="{{ route('documentos.show', [$form->certificado_ingresos_url, time()]) }}" target="_blank" class="text-blue-600 hover:underline">Ver documento</a>
                                </span>
                            @elseif($form->certificado_ingresos)
                                <span class="text-sm text-indigo-700 font-medium">Archivo listo para guardar: {{ $form->certificado_ingresos->getClientOriginalName() }}</span>
                            @else
                                <span class="text-sm text-amber-700">Pendiente por subir.</span>
                            @endif
                        </div>
                    </fieldset>
                </div>
            @endif
        </div>
    </fieldset>
</div>
