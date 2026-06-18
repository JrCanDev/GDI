// Scripts pour la gestion des logs 

// Script permettant d'afficher la div associée à un onglet
function chooseTab(event, category) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("w3-bar-item");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" w3-white w3-text-blue", " w3-blue");
    }
    event.currentTarget.className = event.currentTarget.className.replace(" w3-blue", " w3-white w3-text-blue");
    document.getElementById('div-' + category).style.display = "block";

    adjustSpacer();
    updateInputWidth(category);
}

// Script pour filtrer le tableau à l'aide des inputs de filtrage
function filterTable(tableName) {
    const inputs = document.querySelectorAll(`#filter-inputs-${tableName} .table-input`);
    const table = document.getElementById(`filter-table-${tableName}`);
    if (!table) return;
    const rows = table.getElementsByTagName('tr');
    
    // commence à 1 pour ignorer les en-têtes
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let rowMatches = true;

        for (let j = 0; j < inputs.length; j++) {
            const inputValue = inputs[j].value.toLowerCase().trim();
            if (inputValue) {
                const cellText = cells[j] ? cells[j].textContent.toLowerCase() : '';
                if (!cellText.includes(inputValue)) {
                    rowMatches = false;
                    break;
                }
            }
        }

        rows[i].style.display = rowMatches ? '' : 'none';
    }
    updateInputWidth(tableName);
}

// Script pour mettre à jour la largeur des inputs de filtrage en fonction de la largeur des colonnes
function updateInputWidth(tableName) {
    const table = document.getElementById(`filter-table-${tableName}`);
    const inputs = document.querySelectorAll(`#filter-inputs-${tableName} .table-input`);

    inputs.forEach((input, index) => {
        if (table && table.rows[0] && table.rows[0].cells[index]) {
            input.style.width = table.rows[0].cells[index].offsetWidth - 10 + 'px';
        }
    });
}

const sortStates = {};
function sortTable(tableName, columnName, metadata) {
    if (!sortStates[tableName]) sortStates[tableName] = [];
    if (sortStates[tableName][0] != columnName) sortStates[tableName][0] = columnName;

    const currentDirection = sortStates[tableName][1] || 'DESC';
    const newOrder = currentDirection === 'DESC' ? 'ASC' : 'DESC';
    sortStates[tableName][1] = newOrder;
    
    refreshValues(tableName, metadata);
}

// Rafraîchir les valeurs du tableau de logs via AJAX
function refreshValues(tableName, metadata) {
    if (!metadata) {
        metadata = window.db_listMetadatas[tableName];
    }
    
    $.ajax({
        url: 'controllers/logs/refreshValues.php',
        type: 'GET',
        data: {
            tableName: tableName,
            columnMetadata: metadata,
            sort: sortStates[tableName]
        },
        success: function(response) {
            $('#table-values-' + tableName).html(response);
            adjustSpacer();
            updateInputWidth(tableName);
            filterTable(tableName);
        },
        error: function(response) {
            renderAlertJS('Impossible de contacter le serveur', 'errors');
        }
    });
}

// Exporter directement tous les logs
function exportLogs() {
    window.open('index.php?page=logs&action=export', '_blank');
}

// Écouteurs d'événements pour la saisie sur les champs de filtrage
function initFilters() {
    document.querySelectorAll('.filter-inputs').forEach(inputContainer => {
        const tableName = inputContainer.id.replace('filter-inputs-', '');
        inputContainer.querySelectorAll('.table-input').forEach(input => {
            input.addEventListener('input', () => filterTable(tableName));
            input.addEventListener('change', () => filterTable(tableName));
        });
    });
}

window.addEventListener('load', () => {
    document.querySelectorAll('.filter-inputs').forEach(inputContainer => {
        const tableName = inputContainer.id.replace('filter-inputs-', '');
        updateInputWidth(tableName);
        filterTable(tableName);
    });
    initFilters();
});

window.addEventListener('resize', () => {
    document.querySelectorAll('.filter-inputs').forEach(inputContainer => {
        const tableName = inputContainer.id.replace('filter-inputs-', '');
        updateInputWidth(tableName);
    });
});
