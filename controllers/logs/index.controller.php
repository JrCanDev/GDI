<?php
/**
 * Ce fichier gère l'affichage, le filtrage et l'export des logs.
 */

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/inc/log.php"; // variable globale $LOGFILE

$db = null;
$LISTFK = []; 
$LISTMETADATAS = [
    'logs' => [
        ['name' => 'date', 'type' => 'character varying'],
        ['name' => 'domain', 'type' => 'character varying'],
        ['name' => 'username', 'type' => 'character varying'],
        ['name' => 'message', 'type' => 'text']
    ]
];


if (!isset($_SESSION)) {
    session_start();
}

// On vérifie si l'utilisateur connecté possède les privilèges d'accès à la base de données.
// Si ce n'est pas le cas, on le redirige immédiatement vers la page d'accueil.
if (!authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util'])) {
    header('location: index');
    exit();
}

/**
 * Lit un fichier à l'envers ligne par ligne.
 * 
 * on lit par bloc de 8 ko
 */
function read_file_backwards($file_path, $line_callback) {
    if (!file_exists($file_path) || !is_readable($file_path)) {
        return false;
    }
    
    $file = fopen($file_path, 'rb');
    if (!$file) {
        return false;
    }  

    $chunk_size = 8192; // taille du bloc de lecture : 8 ko = 8192 
    fseek($file, 0, SEEK_END);
    $pos = ftell($file); // position du "curseur" de lecture 
    $buffer = '';

    while ($pos > 0) {
        $read_len = min($pos, $chunk_size);
        $pos -= $read_len;
        fseek($file, $pos, SEEK_SET);
        
        $chunk = fread($file, $read_len);
        $buffer = $chunk . $buffer;

        $lines = explode("\n", $buffer);
        $buffer = array_shift($lines);

        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $line = trim($lines[$i]);
            if ($line !== '') {
                if ($line_callback($line) === false) {
                    fclose($file);
                    return true;
                }
            }
        }
    }

    if ($buffer !== '') {
        $line = trim($buffer);
        if ($line !== '') {
            $line_callback($line);
        }
    }

    fclose($file);
    return true;
}

// GETPOST('action') : exporter les log 
if (GETPOST('action') === 'export') {
    header('Content-Type: text/plain; charset=utf-8');
    header('Content-Disposition: attachment; filename="log_GDI.log"');
    
    read_file_backwards($LOGFILE, function($line) {
        echo $line . "\r\n";
    });
    exit();
}


/**
 * Écrit en html le code pour afficher le tableau des logs
 * 
 * 1. Instancie le conteneur HTML (le bloc '<div>') pour le tableau des logs.
 * 2. Charge la table de logs en appelant getTable.
 *
 * @param PDO $db Instance de connexion PDO (non utilisée directement, conservée pour uniformité avec la BDD).
 * @return void Affiche directement le code HTML généré.
 */
function getTables($db) {
    global $LISTMETADATAS;
    $tableName = 'logs';

    try {
        $div_list = "<div id='div-$tableName' class='w3-bordered w3-border w3-border-blue w3-padding w3-left-align w3-responsive' style='margin-top: 16px;'>";
        $div_list .= "<div id='alerts-$tableName' class='w3-center'></div>";
        $div_list .= getTable($db, $tableName, $LISTMETADATAS[$tableName]);
        $div_list .= "</div>";

        echo $div_list;
    } catch (Throwable $e) {
        echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
    }
}

/**
 * Cette fonction combine le haut du tableau (en-tête, filtres, refresh et export)
 * avec le corps du tableau (les lignes de logs).
 *
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table (ici 'logs').
 * @param array $columnMetadata Structure des colonnes de cette table.
 * @return string Code HTML complet du tableau de logs.
 */
function getTable($db, $tableName, $columnMetadata) {
    return getTableHead($columnMetadata, $tableName) . getTableValue($db, $tableName, $columnMetadata);
}

/**
 * Génération du header du tableau de logs
 * 
 * 1. Le bouton bleu ciel "Refresh values" pour recharger et actualiser les logs.
 * 2. Le bouton vert "Export logs (.txt)" pour exporter l'ensemble des logs.
 * 3. Les champs de saisie de filtrage dynamique pour filtrer en temps réel par colonne.
 * 4. La ligne d'en-tête grise contenant les boutons avec le nom de chaque colonne. Cliquer dessus trie le tableau.
 *
 * @param array $columnMetadata Structure des colonnes.
 * @param string $tableName Nom de la table concernée.
 * @return string Code HTML de l'en-tête et des zones de filtres.
 */
function getTableHead($columnMetadata, $tableName) {
    // bouton rafraîchissement : On passe 'null' à la fonction JavaScript. Elle se rabattra sur window.db_listMetadatas.
    $table_head = "<button class='clickable w3-cyan' id='refresh-values-$tableName' onClick='refreshValues(\"$tableName\", null)'><i class='material-icons' style='padding-top: 5px;'>&#xe5d5;</i> Refresh values</button>";
    $table_head .= "<button class='clickable w3-green' style='margin-left: 8px;' id='export-values-$tableName' onClick='exportLogs()'><i class='material-icons' style='padding-top: 5px;'>&#xe2c4;</i> Export logs (.txt)</button>";
    
    $table_list = "<table class='w3-table w3-bordered w3-border filter-table' id='filter-table-$tableName' style='table-layout: fixed; width: 100%;'><thead class='w3-light-gray'><tr>";
    
    // filtre 
    $filter_list = "<div class='w3-responsive filter-inputs' style='display:flex;' id='filter-inputs-$tableName'>";
    foreach ($columnMetadata as $column) {
        $column_name = sanitize($column['name']);
        $column_type = sanitize($column['type']);

        $filter_list .= "<div class='w3-container' style='padding-left: 5px; padding-right: 5px;'>
        <label for='$column_name' class='w3-text-blue w3-left-align'>
        <p style='margin: 0;'><i>$column_name</i></p>
        </label>";
        
        if ($column_name === 'domain') {
            $filter_list .= "<select id='$column_name' class='w3-select w3-border w3-margin-bottom table-input'>
                <option value=''>Tous</option>
                <option value='login'>login</option>
                <option value='logout'>logout</option>
                <option value='updateDatabase'>updateDatabase</option>
                <option value='removeDatabase'>removeDatabase</option>
                <option value='addDatabase'>addDatabase</option>
                <option value='error'>error</option>
                <option value='addToken'>addToken</option>
                <option value='removeToken'>removeToken</option>
                <option value='resetPassword'>resetPassword</option>
                <option value='updatePassword'>updatePassword</option>
            </select>";
        } else {
            $filter_list .= "<input type='text' id='$column_name' class='w3-input w3-border w3-margin-bottom table-input' placeholder='$column_type'>";
        }
        
        $filter_list .= "</div>";
        
        // Largeurs fixes pour éviter les variations
        $width = '25%';
        if ($column_name === 'date') {
            $width = '18%';
        } elseif ($column_name === 'domain') {
            $width = '15%';
        } elseif ($column_name === 'username') {
            $width = '15%';
        } elseif ($column_name === 'message') {
            $width = '52%';
        }

        // En-tête simple non cliquable
        $table_list .= "<th class='w3-border' style='width: $width; text-align: left; padding: 12px; font-weight: bold;'>$column_name</th>";
    }

    $table_list .= "</tr></thead>";
    $filter_list .= "</div>";

    return $filter_list . $table_head . $table_list;
}

/**
 * Cette fonction lit les entrées du fichier de logs et construit les lignes HTML ('<tbody>') :
 * 1. Elle lit le fichier de logs à l'envers.
 * 2. Elle extrait les informations (date, domaine, utilisateur, message) de chaque ligne de log.
 * 3. Elle affiche les valeurs dans les cellules ('<td>') avec un style adapté (badges de couleur pour les domaines).
 *
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table concernée.
 * @param array $columnMetadata Structure des colonnes de la table.
 * @return string Code HTML contenant toutes les lignes de logs (corps du tableau).
 */
function getTableValue($db, $tableName, $columnMetadata) {
    global $LOGFILE;
    
    try {
        $logs = [];
        if (file_exists($LOGFILE)) {
            read_file_backwards($LOGFILE, function($line) use (&$logs) {
                if (preg_match('/^\[([^\]]+)\]\s+\[([^\]]+)\]\s+\[([^\]]+)\]:\s+(.*)$/', $line, $matches)) {
                    $logs[] = [
                        'date' => $matches[1],
                        'domain' => $matches[2],
                        'username' => $matches[3],
                        'message' => $matches[4]
                    ];
                }
            });
        }

        $table_values = '<tbody id="table-values-' . $tableName . '">';
        $i = 1;
        
        foreach ($logs as $row) {
            $table_values .= "<tr id='tr-$tableName-$i'>";
            foreach ($columnMetadata as $column) {
                $col_name = $column['name'];
                $val = $row[$col_name];
                
                $table_values .= "<td class='w3-border' id='". $column['name'] . "-$i'>";
                if ($col_name === 'domain') {
                    $badgeColor = 'w3-gray';
                    switch ($val) {
                        case 'login': $badgeColor = 'w3-green'; break;
                        case 'logout': $badgeColor = 'w3-blue'; break;
                        case 'addDatabase': case 'addToken': $badgeColor = 'w3-teal'; break;
                        case 'removeDatabase': case 'removeToken': $badgeColor = 'w3-orange w3-text-white'; break;
                        case 'updateDatabase': case 'resetPassword': case 'updatePassword': $badgeColor = 'w3-indigo'; break;
                        case 'error': $badgeColor = 'w3-red'; break;
                    }
                    $table_values .= "<span class='w3-tag w3-round $badgeColor' style='font-size: 11px; font-weight: bold;'>" . sanitize($val) . "</span>";
                } elseif ($col_name === 'username') {
                    $table_values .= "<strong>" . sanitize($val) . "</strong>";
                } elseif ($col_name === 'message') {
                    $table_values .= "<span style='font-family: monospace; font-size: 13px;'>" . sanitize($val) . "</span>";
                } else {
                    $table_values .= sanitize($val);
                }
                $table_values .= "</td>";
            }
            $table_values .= "</tr>";
            $i++;
        }
        $table_values .= "</tbody></table>";

        return $table_values;
    } catch (Throwable $e) {
        echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
    }
}

$pageTitle = 'Journal des logs';
include "$root/views/logs/index.view.php";
