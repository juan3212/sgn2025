document.addEventListener('DOMContentLoaded', function() {
    moveToCell();
    handlePaste();
});

function moveToCell() {
    let table = document.querySelector('.dataTable');

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

function handlePaste() {
    let table = document.querySelector('.dataTable');

    table.addEventListener('paste', function(event) {
        event.preventDefault();

        const clipboardData = event.clipboardData || window.clipboardData;
        const pastedText = clipboardData.getData('text/plain');

        const rows = pastedText.split('\n').filter(row => row.trim() !== '');
        const cells = rows.map(row => row.split('\t'));

        const targetCell = event.target.closest('td');
        if (!targetCell) return;

        const startRowIndex = Array.from(targetCell.parentNode.parentNode.rows).indexOf(targetCell.parentNode);
        const startColIndex = Array.from(targetCell.parentNode.cells).indexOf(targetCell);

        for (let i = 0; i < cells.length; i++) {
            for (let j = 0; j < cells[i].length; j++) {
                const currentRow = table.rows[startRowIndex + i];
                if (!currentRow) continue;

                const currentCell = currentRow.cells[startColIndex + j];
                if (!currentCell) continue;

                const span = currentCell.querySelector('span');
                if (span) {
                    span.textContent = cells[i][j];
                }
            }
        }
    });
}