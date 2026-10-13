document.addEventListener('DOMContentLoaded', function() {
    moveToCell();
    handlePaste();
});

function moveToCell() {
    let table = document.querySelector('.dataTable');

    this.addComments();

    table.addEventListener('click', function(event) {
        const cell = event.target;

        if (cell.tagName === 'SPAN') {
            cell.contentEditable = true;
            cell.focus();
            selectText(cell);
        }
    });


    table.addEventListener('keydown', function(event) {
        const cell = event.target;

        if (cell.tagName === 'SPAN') {
            const keyCode = event.keyCode;
            const nextCell = getNextCell(cell.parentElement, keyCode);

            if (nextCell) {
                nextCell.contentEditable = true;
                nextCell.focus();
                event.preventDefault();

                selectText(nextCell);
            }
        }
    });

    function getNextCell(cell, keyCode) {
        let nextCell;

        switch (keyCode) {
            case 37: // Flecha izquierda
                nextCell = cell.previousElementSibling;
                break;
            case 38: // Flecha arriba
                nextCell = cell.parentNode.previousElementSibling?.children[cell.cellIndex];
                break;
            case 39: // Flecha derecha
                nextCell = cell.nextElementSibling;
                break;
            case 40: // Flecha abajo
                nextCell = cell.parentNode.nextElementSibling?.children[cell.cellIndex];
                break;
        }

        return nextCell?.querySelector('span');
    }

    function selectText(element) {
        if (element && typeof window.getSelection !== 'undefined') {
            const range = document.createRange();
            range.selectNodeContents(element);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
}

function handlePaste(event) {
    let table = document.querySelector('.dataTable');
    if (!table) return;

    if (event && event.type === 'paste') {
        processPasteEvent(event, table);
        return;
    }

    if (table.dataset.pasteListener) return;
    table.dataset.pasteListener = 'true';

    table.addEventListener('paste', function(e) {
        processPasteEvent(e, table);
    });
}

function processPasteEvent(event, table) {
    if (event.defaultPrevented) return;

    // Si el evento ocurrió dentro de un campo de formulario (input, textarea, select), permitir pegado nativo
    if (event.target.closest('input, textarea, select')) {
        return;
    }

    // Si el evento ocurrió en un elemento contenteditable fuera de la tabla, permitir pegado nativo
    if (event.target.isContentEditable && !table.contains(event.target)) {
        return;
    }

    const targetCell = event.target.closest('td');
    if (!targetCell || !table.contains(targetCell)) return;

    event.preventDefault();

    const clipboardData = event.clipboardData || window.clipboardData;
    const pastedText = clipboardData ? clipboardData.getData('text/plain') : '';
    if (!pastedText) return;

    const rows = pastedText.split(/\r\n|\n|\r/).filter(row => row.trim() !== '');
    if (rows.length === 0) return;

    const cells = rows.map(row => row.split('\t').map(c => c.trim()));

    const targetRow = targetCell.closest('tr');
    if (!targetRow || !targetRow.parentNode) return;

    // Trabajar directamente con las filas del tbody para evitar desfases con thead
    const tbody = table.querySelector('tbody') || targetRow.parentNode;
    const tbodyRows = Array.from(tbody.querySelectorAll('tr'));
    const startTbodyRowIndex = tbodyRows.indexOf(targetRow);
    if (startTbodyRowIndex === -1) return;

    // Spans editables de la fila seleccionada
    const rowEditableSpans = Array.from(targetRow.querySelectorAll('.editable-cell, .comentario-periodo'));
    if (rowEditableSpans.length === 0) return;

    // Identificar en cuál span específico se hizo clic o foco
    const clickedSpan = event.target.closest('span.editable-cell, span.comentario-periodo');
    let startSpanIndex = clickedSpan ? rowEditableSpans.indexOf(clickedSpan) : -1;

    // Verificar si la primera columna pegada es una nota (número o NA o vacío)
    const firstVal = cells[0][0] ? cells[0][0].toLowerCase() : '';
    const isFirstColGrade = firstVal === '' || firstVal === 'na' || !isNaN(parseFloat(firstVal.replace(',', '.')));

    let colOffset = 0;
    // Si el usuario copió desde Excel incluyendo las columnas de Nombre y Apellido (texto al inicio)
    if (!isFirstColGrade && cells[0].length > rowEditableSpans.length) {
        colOffset = 2; // Omitir las dos primeras columnas (Nombre y Apellido)
        if (startSpanIndex === -1) {
            startSpanIndex = 0;
        }
    } else if (startSpanIndex === -1) {
        // El usuario hizo clic en la fila pero no en un span editable (ej. en el nombre)
        startSpanIndex = 0;
    }

    for (let i = 0; i < cells.length; i++) {
        const currentRow = tbodyRows[startTbodyRowIndex + i];
        if (!currentRow) break;

        const currentSpans = Array.from(currentRow.querySelectorAll('.editable-cell, .comentario-periodo'));

        for (let j = colOffset; j < cells[i].length; j++) {
            const targetSpanIndex = startSpanIndex + (j - colOffset);
            if (targetSpanIndex >= currentSpans.length) break;

            const span = currentSpans[targetSpanIndex];
            if (span) {
                span.textContent = cells[i][j];
                // Disparar evento input para que se ejecute la validación de rangos y habilitación de guardado
                span.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }
}

function addComments() {
    let table = document.querySelector('.dataTable');
    let availableComments = ['NA']
    table.addEventListener('contextmenu', function(event) {
        event.preventDefault();
        const targetCell = event.target;
        if (targetCell.nodeName == 'SPAN') {
            targetCell.textContent = availableComments[0];
        };

    });
}
