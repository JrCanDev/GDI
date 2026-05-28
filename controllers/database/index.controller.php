<?php
/**
 * Ce fichier gère l'affichage, le filtrage, le tri et les actions de modification ou de suppression 
 * des données de la base de données.
 */

$db = require "$root/lib/pdo.php";
$LISTFK = []; 
$LISTMETADATAS = [];


if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);


// On vérifie si l'utilisateur connecté possède les privilèges d'accès à la base de données.
// Si ce n'est pas le cas, on le redirige immédiatement vers la page d'accueil.
if (!authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util'])) {
  header('location: index');
  exit();
}


/**
 * Écrit en html le code pour afficher les table
 * 
 * 1. Récupère la liste de toutes les tables du schéma public de la base de données.
 * 2. Génère le menu d'onglets bleus (boutons) visibles en haut de la page pour naviguer entre les tables.
 * 3. Instancie les conteneurs HTML (les blocs '<div>') pour chaque table (les tableaux eux-mêmes).
 * 4. Charge les métadonnées et clés étrangères de chaque table pour les transmettre au JavaScript.
 *
 * @param PDO $db Instance de connexion PDO à la base de données.
 * @return void Affiche directement le code HTML généré pour les onglets et les conteneurs.
 */
function getTables($db) {
  global $LISTFK;
  global $LISTMETADATAS;

  try {
    // Récupération de la liste de toutes les tables de l'application (schéma public standard de Postgres)
    $query = "SELECT table_name ";
    $query .= "FROM information_schema.tables ";
    $query .= "WHERE table_schema = 'public' AND table_type = 'BASE TABLE' ";
    $query .= "ORDER by table_name";
    $statement = $db->query($query);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    $table_list = "<div class='w3-bar w3-full w3-margin-top'>";
    $div_list = '';

    // On boucle une première fois pour pré-charger en PHP toutes les métadonnées (variable globale $LISTMETADATAS) 
    // et clés étrangères (variable globale $LISTFK) de chaque table. 
    foreach ($result as $row) {
      $LISTMETADATAS[sanitize($row['table_name'])] = getTableMetadata($db, sanitize($row['table_name']));

      $table_fk = getListFK($db, sanitize($row['table_name']));
      if (!is_null($table_fk)) {
        $LISTFK[sanitize($row['table_name'])] = $table_fk;
      }
    }


    // Génération des boutons d'onglets pour basculer d'une table à l'autre,
    // et création des blocs <div> (qui contiendront les tableaux complets générés par getTable)
    foreach ($result as $row) {
      $tableName = sanitize($row['table_name']);

      $table_list .= "<button class='w3-bar-item w3-button w3-border w3-border-blue w3-blue' onclick=\"chooseTab(event, '$tableName')\">$tableName</button>";
      $div_list .= "<div id='div-$tableName' class='tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align w3-responsive' style='display: none;'>";
      $div_list .= "<div id='alerts-$tableName' class='w3-center'></div>";
      $div_list .= getTable($db, $tableName, $LISTMETADATAS[$tableName]);
      $div_list .= "</div>";
    }
    $table_list .= "</div>";

    echo $table_list;
    echo $div_list;
  } catch (Throwable $e) {
    echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
  }
}


/**
 * Cette fonction combine le haut du tableau (en-tête, filtres, refresh et bouton "+")
 * avec le corps du tableau (les lignes de données contenant les boutons modifier/supprimer).
 *
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table à assembler.
 * @param array $columnMetadata Structure des colonnes de cette table (types, contraintes, etc.).
 * @return string Code HTML complet du tableau de données.
 */
function getTable($db, $tableName, $columnMetadata) {
  $table = getTableHead($columnMetadata, $tableName) . getTableValue($db, $tableName, $columnMetadata);
  return $table;
}


/**
 * Génération du header des table
 * 
 * 1. Le bouton bleu ciel "Refresh values" pour recharger et actualiser les données.
 * 2. Les champs de saisie de filtrage dynamique (les inputs bleus) pour filtrer par colonne.
 * 3. La ligne d'en-tête grise contenant les boutons avec le nom de chaque colonne. Cliquer dessus trie le tableau.
 * 4. Le bouton vert "+" tout à droite pour insérer une nouvelle ligne.
 *
 * @param array $columnMetadata Structure des colonnes (utilisée pour créer les inputs avec les bons placeholders de types).
 * @param string $tableName Nom de la table concernée.
 * @return string Code HTML de l'en-tête et des zones de filtres.
 */
function getTableHead($columnMetadata, $tableName) {
  // bouton rafraîchissement : On passe 'null' à la fonction JavaScript. Elle se rabattra sur window.db_listMetadatas.
  $table_list = "<button class='clickable w3-cyan' id='refresh-values-$tableName' onClick='refreshValues(\"$tableName\", null)'><i class='material-icons' style='padding-top: 5px;'>&#xe5d5;</i> Refresh values</button>";
  $table_list .= "<table class='w3-table w3-bordered w3-border filter-table' id='filter-table-$tableName'><thead class='w3-light-gray'><tr>";
  
  // filtre 
  $filter_list = "<div class='w3-responsive filter-inputs' style='display:flex;' id='filter-inputs-$tableName'>";
  foreach ($columnMetadata as $column) {
    $column_name = sanitize($column['name']);
    $column_type = sanitize($column['type']);

    $filter_list .= "<div class='w3-container' style='padding-left: 5px; padding-right: 5px;'>
    <label for='$column_name' class='w3-text-blue w3-left-align'>
    <p style='margin: 0;'><i>$column_name</i></p>
    </label>
    <input type='text' id='$column_name' class='w3-input w3-border w3-margin-bottom table-input' placeholder='$column_type' " . ($column_name === 'annee_scolaire' ? "value='" . SELECTED_YEAR . "'" : '') . ">
    </div>";
    
    // En-tête cliquable pour trier la table 
    $table_list .= "<th class='w3-border'><button class='w3-button' style='width:100%; height: 100%' onClick='sortTable(\"$tableName\", \"$column_name\", null)'>$column_name</button></th>";
  }

  // Bouton vert "+" pour ajouter une nouvelle ligne à la table
  $table_list .= "<th colspan='2' class='w3-blue-grey w3-border-blue-grey' style='width: 5%'><button class='clickable w3-green' id='new-value-$tableName' onClick='newValue(\"$tableName\", null)'><i class='material-icons' style='padding-top: 5px;'>&#xe145;</i></button></th>";
  $table_list .= "</thead>";
  $filter_list .= "</div>";

  $table_head = $filter_list;
  $table_head .= $table_list;

  return $table_head;
}


/**
 * Cette fonction extrait les lignes de données de la base de données et construit les lignes HTML ('<tbody>') :
 * 1. Elle parcourt chaque enregistrement de la table.
 * 2. Elle affiche les valeurs dans les cellules ('<td>'), après formatage/validation de type.
 * 3. Elle ajoute les boutons d'action à droite de chaque ligne :
 *    - Bouton vert "Crayon" : permet de modifier les valeurs de la ligne en éditant ses cellules.
 *    - Bouton rouge "Poubelle" : permet de supprimer définitivement la ligne.
 *
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table concernée.
 * @param array $columnMetadata Structure des colonnes de la table.
 * @return string Code HTML contenant toutes les lignes de données (corps du tableau).
 */
function getTableValue($db, $tableName, $columnMetadata) {
  global $LISTFK;
  
  try {
    // Récupération de tous les enregistrements de la table concernée
    $query = "SELECT * ";
    $query .= "FROM $tableName ";
    $statement = $db->query($query);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    $table_values = '<tbody id="table-values-' . $tableName . '">';
    $i = 1;
    
    // Génération des lignes de données
    foreach ($result as $row) {
      $value_list = [];
      $table_values .= "<tr id='tr-$tableName-$i'>";
      foreach ($columnMetadata as $column) {
        $column_name = $column['name'];
        $column_type = $column['type'];
        $column_value = $row[$column_name];
        $value_list[$column_name] = $column_value;

        // validateTypeInbound formate et valide la donnée brute SQL pour un affichage propre à l'utilisateur
        $table_values .= "<td class='w3-border' id='". $column['name'] . "-$i'>" . validateTypeInbound($column_value, $column_type) . "</td>";
      }

      // Bouton vert Crayon (Modification)
      // Note : On passe 'null' pour les métadonnées. Le JS les récupère dynamiquement dans les globales.
      $table_values .= "<td class='w3-blue-grey w3-border-blue-grey' style='width: 5%'><button id='modify-value-$tableName-$i' class='clickable w3-light-green' onClick=\"modifyValue('$tableName', null, $i, " . sanitize(json_encode($value_list)) . ")\"><i class='material-icons' style='padding-top: 5px;'>&#xe254;</i></button></td>";
      
      // Bouton rouge Poubelle (Suppression)
      // Note : On passe 'null' pour les métadonnées et les FK afin d'éviter la duplication de chaînes JSON volumineuses.
      $table_values .= "<td class='w3-blue-grey w3-border-blue-grey' style='width: 5%'><button id='delete-value-$tableName-$i' class='clickable w3-red' onClick=\"deleteValue('$tableName', null, null, $i, " . sanitize(json_encode($value_list)) . ")\"><i class='material-icons' style='padding-top: 5px;'>&#xe92b;</i></button></td>";
      $table_values .= "</tr>";
      $i += 1;
    }
    $result = null;
    $table_values .= "</tbody></table>";

    return $table_values;
  } catch (Error | Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
  }
}


/**
 * Obtient les métadonnées d'une table (nom, type, nullabilité, clés primaires et clés étrangères des colonnes).
 * 
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table concernée.
 * @return array Un tableau associatif contenant toute la description des colonnes.
 */
function getTableMetadata($db, $tableName) {
  try {
    // Récupération des propriétés structurelles 
    $query = "SELECT column_name as name, data_type as type, character_maximum_length as maximum, is_nullable as nullable ";
    $query .= "FROM information_schema.columns ";
    $query .= "WHERE table_name = :table_name ";
    $query .= "ORDER BY ordinal_position";
    $statement = $db->prepare($query);
    $statement->bindParam(':table_name', $tableName, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    // vérification clé étrangère 
    $query_fk = "SELECT tc.constraint_name, tc.table_name, kcu.column_name, ccu.table_name AS foreign_table_name, ccu.column_name AS foreign_column_name ";
    $query_fk .= "FROM information_schema.table_constraints AS tc 
      JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
      JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name ";
    $query_fk .= "WHERE tc.constraint_type = 'FOREIGN KEY'
      AND tc.table_name = :table_name
      AND kcu.column_name = :column_name";

    // vérification clé primaire
    $query_pk = "SELECT kcu.column_name ";
    $query_pk .= "FROM information_schema.table_constraints tc
      JOIN information_schema.key_column_usage kcu 
        ON tc.constraint_name = kcu.constraint_name ";
    $query_pk .= "WHERE tc.constraint_type = 'PRIMARY KEY'
      AND tc.table_name = :table_name
      AND kcu.column_name = :column_name";

    // Pour chaque colonne trouvée, on exécute les requêtes de recherche de contraintes (PK et FK)
    foreach ($result as &$column) {
      $statement = $db->prepare($query_fk);
      $statement->bindParam(':table_name', $tableName, PDO::PARAM_STR);
      $statement->bindParam(':column_name', $column['name'], PDO::PARAM_STR);
      $statement->execute();
      $column_fk = $statement->fetch(PDO::FETCH_ASSOC);
      $statement->closeCursor();

      $statement = $db->prepare($query_pk);
      $statement->bindParam(':table_name', $tableName, PDO::PARAM_STR);
      $statement->bindParam(':column_name', $column['name'], PDO::PARAM_STR);
      $statement->execute();
      $column_pk = $statement->fetch(PDO::FETCH_ASSOC);
      $statement->closeCursor();

      // On enrichit le tableau de la colonne avec ses informations de clés
      $column['fk'] = $column_fk;
      $column['pk'] = $column_pk;
    }
    unset($column);

    return $result;
  } catch (Throwable $e) {
    echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
  }
}


/**
 * Cette fonction recherche uniquement toutes les relations de clé étrangère rattachées à la table.
 * Elle permet d'identifier quelles colonnes font référence à des enregistrements d'autres tables
 * afin que l'interface puisse générer des listes déroulantes de choix 
 *
 * @param PDO $db Instance de connexion PDO.
 * @param string $tableName Nom de la table concernée.
 * @return array|null Un tableau contenant les clés étrangères ou null s'il n'y en aucune.
 */
function getListFK($db, $tableName) {
  try {
    // récupération des clé étrangère 
    $query_fk = "SELECT kcu.column_name, ccu.table_name AS foreign_table_name, ccu.column_name AS foreign_column_name ";
    $query_fk .= "FROM information_schema.table_constraints AS tc 
      JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
      JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name ";
    $query_fk .= "WHERE tc.constraint_type = 'FOREIGN KEY'
      AND tc.table_name = :table_name";
      
    $statement = $db->prepare($query_fk);
    $statement->bindParam(':table_name', $tableName, PDO::PARAM_STR);
    $statement->execute();
    $columns_fk = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    if ($columns_fk !== []) {
      return $columns_fk;
    }

    return null;
  } catch (Throwable $e) {
    echo 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile();
  }
}

$pageTitle = 'Base de données';
include "$root/views/database/index.view.php";