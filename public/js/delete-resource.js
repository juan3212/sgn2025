export function deleteResource({
    controllerName, 
    functionName = 'delete', 
    resourceId, 
    confirmMessage = '¿Estás seguro de que quieres eliminar este elemento?',
    successMessage = 'Elemento eliminado exitosamente',
    onSuccessCallback, 
    onErrorCallback,
    redirectUrl = null,
    reloadTable = false,
    tableId = null
}) {
    if (confirm(confirmMessage)) {
        fetch('/generic-delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                controller: controllerName,
                functionName: functionName,
                id: resourceId
            }),
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Error al eliminar el elemento.');
                });
            }
        })
        .then(data => {
            console.log('Respuesta exitosa de eliminación:', data);
            
            // Mostrar mensaje de éxito con SweetAlert2
            Swal.fire({
                title: '¡Eliminado!',
                text: data.success || successMessage,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Manejar redirección si se proporciona URL
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                    return;
                }
                
                // Recargar tabla si se especifica
                if (reloadTable && tableId) {
                    // Recargar la tabla DataTable
                    $(`#${tableId}`).DataTable().ajax.reload();
                }
                
                // Ejecutar callback de éxito
                if (onSuccessCallback) {
                    onSuccessCallback(data);
                }
            });
        })
        .catch(error => {
            console.error('Error en la eliminación:', error);
            
            Swal.fire({
                title: '¡Error!',
                text: error.message || 'Error desconocido al eliminar.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            
            if (onErrorCallback) {
                onErrorCallback(error);
            }
        });
    }
}

/*
Uso básico
deleteResource({
    controllerName: 'User',
    resourceId: 123
});

Uso avanzado
deleteResource({
    controllerName: 'Product',
    functionName: 'softDelete',
    resourceId: 456,
    confirmMessage: '¿Seguro que quieres archivar este producto?',
    successMessage: 'Producto archivado correctamente',
    reloadTable: true,
    tableId: 'products-table',
    onSuccessCallback: (data) => {
        // Actualizar contador en la interfaz
        updateProductCount();
    }
});
*/