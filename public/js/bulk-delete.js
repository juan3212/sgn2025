export class Delete {
    constructor(tableName, modelName) {
        this.modelName = modelName;
        this.tableName = tableName;
        this.selectedIds = [];
        this.initCheckboxes();
    }

    bulkDelete(ids) {
        if (!ids || ids.length === 0) return;
        
        fetch('/bulk-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                model: this.modelName,
                functionName: 'bulkDelete',
                ids: ids
            }),
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Error al eliminar los elementos.');
                });
            }
        })
        .then(data => {
            console.log('Respuesta exitosa de eliminación masiva:', data);
            
            // Mostrar mensaje de éxito con SweetAlert2
            Swal.fire({
                title: '¡Eliminados!',
                text: data.success || `Se han eliminado ${ids.length} elementos correctamente.`,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Recargar la tabla después de la eliminación
                $('#' + this.tableName).DataTable().ajax.reload();
                
                // Ocultar el botón de eliminar seleccionados
                document.getElementById('delete-selected').classList.add('hidden');
                
                // Deseleccionar el checkbox "Seleccionar todos"
                document.getElementById('select-all').checked = false;
            });
        })
        .catch(error => {
            console.error('Error en la eliminación masiva:', error);
            
            Swal.fire({
                title: '¡Error!',
                text: error.message || 'Error desconocido al eliminar los elementos.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }

    initCheckboxes() {
        const self = this; // Store reference to the class instance
        
        // Manejar el evento click en el checkbox "Seleccionar todos"
        $('#select-all').on('click', function() {
            const isChecked = this.checked;
            
            // Seleccionar o deseleccionar todos los checkboxes visibles
            $('.select-checkbox').each(function() {
                this.checked = isChecked;
                
                const id = $(this).data('id');
                
                if (isChecked) {
                    // Añadir ID si no está ya en el array
                    if (!self.selectedIds.includes(id)) {
                        self.selectedIds.push(id);
                    }
                } else {
                    // Eliminar ID del array
                    self.selectedIds = self.selectedIds.filter(item => item !== id);
                }
            });
            
            // Mostrar u ocultar el botón de eliminar seleccionados
            self.toggleDeleteButton();
        });
        
        // Manejar el evento click en los checkboxes individuales
        $('#' + this.tableName).on('click', '.select-checkbox', function() {
            const id = $(this).data('id');
            
            if (this.checked) {
                // Añadir ID si no está ya en el array
                if (!self.selectedIds.includes(id)) {
                    self.selectedIds.push(id);
                }
            } else {
                // Eliminar ID del array
                self.selectedIds = self.selectedIds.filter(item => item !== id);
                
                // Deseleccionar el checkbox "Seleccionar todos" si algún elemento no está seleccionado
                $('#select-all').prop('checked', false);
            }
            
            // Mostrar u ocultar el botón de eliminar seleccionados
            self.toggleDeleteButton();
        });
        
        // Manejar el evento click en el botón de eliminar seleccionados
        document.getElementById('delete-selected').addEventListener('click', function() {
            if (self.selectedIds.length === 0) return;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar ${self.selectedIds.length} elementos seleccionados?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    self.bulkDelete(self.selectedIds);
                    // Resetear el array de IDs seleccionados
                    self.selectedIds = [];
                }
            });
        });    
    }

    toggleDeleteButton() {
        const deleteButton = document.getElementById('delete-selected');
        
        if (this.selectedIds.length > 0) {
            deleteButton.classList.remove('hidden');
        } else {
            deleteButton.classList.add('hidden');
        }
    }
}