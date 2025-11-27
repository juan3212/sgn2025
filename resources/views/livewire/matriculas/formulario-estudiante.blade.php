<div>
    <livewire:matriculas.components.info-basica-usuario wire:key="info_estudiante" :$estudianteId />
    <livewire:matriculas.components.info-contacto tipoUsuario="estudiante" wire:key="contacto_estudiante" :$estudianteId />
    <livewire:matriculas.components.info-medica wire:key="medica" :$estudianteId />
   <livewire:matriculas.components.upload-document tipoDocumento="Documento de identidad" usuario="estudiante" nombreDocumento="documento_identidad" wire:key="documento" :$estudianteId :usuarioId="$estudianteId" />
</div>
