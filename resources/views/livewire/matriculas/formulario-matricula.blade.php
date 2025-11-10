
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-xl">

   <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Formulario de Registro</h1>

   <form wire:submit.prevent="mostrarDatos" enctype="multipart/form-data" class="space-y-8">
      <div>
         <livewire:matriculas.formulario-estudiante wire:key="estudiante" :$estudianteId />
         <hr class="border-t-2 border-dashed border-gray-400 my-8">
         <h2 class="text-2xl font-bold text-gray-800 pt-4">Información de Acudiente</h2>
         <livewire:matriculas.formulario-acudiente wire:key="acudiente" :$estudianteId />
      </div>

      <div class="fixed bottom-6 right-6">
         <button type="submit"
            class="p-4 px-8 bg-indigo-600 text-white text-lg font-bold rounded-full shadow-xl hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">
            Finalizar Matrícula
         </button>
      </div>

   </form>
</div>