<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once "$root/vendor/autoload.php";
require_once "$root/lib/project.lib.php";
$db = require "$root/lib/pdo.php";
const MAX_CELL = 4;

if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);

$filters = GETPOST("filters");
if (is_array($filters)) {
  $i = 0;

  foreach ($filters as $key => $value) {
    if (empty($value)) {
      unset($filters[$key]);
      $i++;
    } else {
      $filters[$key] = sanitize($value);
    }
  }
  if ($i === 4) {
    $filters = null;
  }

} else {
  $filters = null;
}

try {
  function initTables($db) {
    $year1 = substr(SELECTED_YEAR, 0, 4);
    $year2 = substr(SELECTED_YEAR, 5, 4);
    $date_august = new DateTime("$year1-08-30");
    $week_num = $date_august->format("W");

    try {
      $query = "SELECT num_sem
                FROM semaines
                WHERE (num_sem like '$year1%'
                OR num_sem like '$year2%')
                AND num_sem >= '$year1-$week_num'
                ORDER BY num_sem";

      $stmt = $db->query($query);

      if ($stmt === false) {
        echo "<p class='w3-alert'>Cette année </p>";
      }
      $weeks = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt->closeCursor();

      $i = 0;
      $dividend = 0;
      $list_week = [];
      $list_tables = '';
      foreach ($weeks as $week) {
        $i++;
        $list_week[] = $week['num_sem'];

        $dividend = fmod($i, MAX_CELL);
        if ($dividend == 0) {
          $list_tables .= buildTable($db, $list_week);
          $list_week = [];
        }
      }
      if ($dividend != 0) {
        for ($dividend; $dividend < MAX_CELL; $dividend++) {
          $list_week[] = '';
        }
        $list_tables .= buildTable($db, $list_week);
      }
      echo $list_tables;
    } catch (Throwable $e) {
      echo die(json_encode(['error' => 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile()]));
    }
  }

  function buildTable($db, $weeks) {
    $table_list = "<table class='w3-table w3-bordered w3-border maquette-table'><thead class='w3-cyan'>";
    $table_list .= getTableHead($weeks);
    $table_list .= "</thead></thead><tbody id='table-values-semaines'>";
    $table_list .= getTableCells($db, $weeks);
    $table_list .= "</tbody></table>";
    return $table_list;
  }

  function getTableHead($weeks) {
    $table_list = "<tr>";
    foreach ($weeks as $week) {
      $table_list .= "<th class='w3-border'>$week</th>";
    }
    $table_list .= "</tr>";
    return $table_list;
  }

  function getTableCells($db, $weeks) {
    global $filters;

    $table_value = "<tr>";
    $table_time = "<tr>";
    $table_modal = '';
    $table_modal2 = '';
    foreach ($weeks as $week) {
      if ($week != '') {
        try {
          $query = "SELECT num_sem, nom_ens, prenom_ens, id_cours, type_seance, duree_semaine, intitule_cours, commentaire_cours
          FROM maquette_ens
          WHERE num_sem = :num_sem ";

          $params = [ 'num_sem' => $num_sem ]; // Assure-toi que $num_sem est défini

          if ($filters !== null) {
            $filterClauses = [];

            foreach ($filters as $key => $value) {
              if ($key === 'type_seance' && strpos($value, ';') !== false) {
                // Traitement spécial : plusieurs valeurs séparées par ;
                $parts = explode(';', $value);
                $orClauses = [];
                foreach ($parts as $i => $part) {
                  $paramName = $key . '_' . $i;
                  $orClauses[] = "$key ilike :$paramName";
                  $params[$paramName] = '%' . trim($part) . '%';
                }
                $filterClauses[] = '(' . implode(' OR ', $orClauses) . ')';
              } else {
                // Cas standard
                $filterClauses[] = "$key ilike :$key";
                $params[$key] = '%' . trim($value) . '%';
              }
            }

            if (!empty($filterClauses)) {
              $query .= 'AND ' . implode(' AND ', $filterClauses) . ' ';
            }
          }

          // Clause sur l'année scolaire
          $query .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ORDER BY id_cours";
          
          $stmt = $db->prepare($query);
          $stmt->bindParam(':num_sem', $week);
          
          if ($filters !== null) {
            foreach ($filters as $key => $value) {
              if ($key === 'type_seance' && strpos($value, ';') !== false) {
                // Plusieurs valeurs pour type_seance
                $parts = explode(';', $value);
                foreach ($parts as $i => $part) {
                  $paramName = ':' . $key . '_' . $i;
                  $stmt->bindValue($paramName, '%' . trim($part) . '%');
                }
              } else {
                // Cas standard
                $stmt->bindValue(':' . $key, '%' . trim($value) . '%');
              }
            }
          }
          
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $stmt->closeCursor();
          
          $total_heures = 0;
          if (count($result) == 0) {
            $table_value .= "<td class='w3-border maquette-cell-empty'><p class='maquette-paragraph'>Aucun cours</p></td>";
          } else {
            $table_value .= "<td class='w3-border maquette-cell'><button onclick=\"toggleModal('$week')\" class='hidden-button' style='width: 100%; height: 100%;'>";

            $i = 0;
            foreach ($result as $value) {
              if ($value !== false) {
                $ens = sanitize($value['nom_ens']) . ' ' . sanitize($value['prenom_ens']);
                $id_cours = sanitize($value['id_cours']);
                $type_seance = sanitize($value['type_seance']);
                $intitule_cours = sanitize($value['intitule_cours']);
                $heures = sanitize($value['duree_semaine']);
                $total_heures += $heures;
                
                if ($i < MAX_CELL) {
                  $table_value .= "<p class='maquette-paragraph'>$id_cours - $ens - $type_seance : ${heures}H</p>";
                  $i++;
                }
                $table_modal2 .= "<div class='modal-line'><p class='maquette-paragraph'>$id_cours - $intitule_cours - $ens - $type_seance : $heures H</p></div>";
              }
            }
          }
          $table_time .= "</button><td class='w3-border w3-light-blue'>Total: $total_heures H</td>";
          $table_modal .= "<div id='$week' class='w3-modal'>
            <div class='w3-modal-content'>
              <header class='w3-container w3-teal'> 
                <span onclick=\"toggleModal('$week')\" 
                class='w3-button w3-display-topright'>&times;</span>
                <h2>Cours de la semaine : $week - $total_heures H</h2>
              </header>
              <div class='w3-container'>" . $table_modal2 . "</div></div></div>";
          $table_modal2 = '';

        } catch (Throwable $e) {
          echo die(json_encode(['error' => 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile()]));
        }
      } else {
        $table_value .= "<td class='w3-border maquette-cell-dead'></td>";
        $table_time .= "<td class='w3-border w3-light-blue'></td>";
      }
    }
    $table_value .= "</tr>";
    $table_time .= "</tr></tbody></table>";
    $table_list = "$table_value $table_time $table_modal";
    return $table_list;
  }

  initTables($db);

} catch (Throwable $e) {
  echo die(json_encode(['error' => 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile()]));
}
