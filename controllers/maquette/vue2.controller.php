<?php

define('SEMESTER_1', '1');
define('SEMESTER_2', '2');
define('SEMESTER_3', '3');
define('SEMESTER_3APP', '3APP');
define('SEMESTER_4', '4');
define('SEMESTER_4APP', '4APP');
define('SEMESTER_5', '5');
define('SEMESTER_5A', '5A');
define('SEMESTER_5B', '5B');
define('SEMESTER_6', '6');
define('SEMESTER_6A', '6A');
define('SEMESTER_6B', '6B');

$pageTitle = 'Maquette des ressources : Heures par semestres';
$week_names = [];

$year1 = substr(SELECTED_YEAR, 0, 4);
$year2 = substr(SELECTED_YEAR, 5, 4);

try {
  $query = "SELECT num_sem
            FROM semaines
            WHERE num_sem LIKE :year1_pattern
            OR num_sem LIKE :year2_pattern";

  $stmt = $db->prepare($query);
  $stmt->bindValue(':year1_pattern', $year1 . '%', PDO::PARAM_STR);
  $stmt->bindValue(':year2_pattern', $year2 . '%', PDO::PARAM_STR);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $i = 0;
  foreach ($results as $week) {
    $i++;
    $week_names[$week['num_sem']] = $i;
  }

  $stmt->closeCursor();
} catch (PDOException $e) {
  error_log("Database error: " . $e->getMessage());
  echo "<p class='w3-text-red'>Erreur : Problème de base de données. Veuillez consulter les journaux du serveur.</p>";
  return;
} catch (Throwable $e) {
  error_log("General error: " . $e->getMessage());
  echo "<p class='w3-text-red'>Erreur : Une erreur s'est produite. Veuillez consulter les journaux du serveur.</p>";
  return;
}

function getSearchCondition($semester) {
  switch ($semester) {
    case SEMESTER_1:
      return "(id_cours LIKE 'R1.%' or id_cours LIKE 'P1.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE1.%' and annee_scolaire = '".SELECTED_YEAR."'))";
    case SEMESTER_2:
      return "(id_cours LIKE 'R2.%' or id_cours LIKE 'P2.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE2.%' and annee_scolaire = '".SELECTED_YEAR."'))";
    case SEMESTER_3:
      return "(id_cours LIKE 'R3.%' or id_cours LIKE 'P3.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE3.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM' or type_seance LIKE 'TD%' or type_seance LIKE 'TP%' or type_seance = 'SAE-FI') ";
    case SEMESTER_3APP:
      return "(id_cours LIKE 'R3.%' or id_cours LIKE 'P3.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE3.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM-APP' or type_seance = 'TD3' or type_seance = 'TPE' or type_seance = 'SAE-APP') ";
    case SEMESTER_4:
      return "(id_cours LIKE 'R4.%' or id_cours LIKE 'P4.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE4.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM' or type_seance LIKE 'TD%' or type_seance LIKE 'TP%' or type_seance = 'SAE-FI')
              AND NOT type_seance = 'TD3'
              AND NOT type_seance = 'TPE' ";
    case SEMESTER_4APP:
      return "(id_cours LIKE 'R4.%' or id_cours LIKE 'P4.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE4.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM-APP' or type_seance = 'TD3' or type_seance = 'TPE' or type_seance = 'SAE-APP') ";
    case SEMESTER_5:
      return "(id_cours LIKE 'R5.%' or id_cours LIKE 'P5.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE5.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM' or type_seance = 'TD1' or type_seance = 'TPA' or type_seance = 'TPB' or type_seance = 'SAE-FI') ";
    case SEMESTER_5A:
      return "(id_cours LIKE 'R5.%' or id_cours LIKE 'P5.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE5.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND id_cours NOT LIKE 'R5.B%'
              AND (type_seance = 'CM-APP' or type_seance = 'TD2' or type_seance = 'TPC' or type_seance = 'SAE-APP')
              AND NOT type_seance = 'TD3'
              AND NOT type_seance = 'TPD' ";
    case SEMESTER_5B:
      return "(id_cours LIKE 'R5.%' or id_cours LIKE 'P5.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE5.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND id_cours NOT LIKE 'R5.A%'
              AND (type_seance = 'CM-APP' or type_seance = 'TD3' or type_seance = 'TPD' or type_seance = 'SAE-APP')
              AND NOT type_seance = 'TD2'
              AND NOT type_seance = 'TPC' ";
    case SEMESTER_6:
      return "(id_cours LIKE 'R6.%' or id_cours LIKE 'P6.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE6.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND (type_seance = 'CM' or type_seance = 'TD1' or type_seance = 'TPA' or type_seance = 'TPB' or type_seance = 'SAE-FI') ";
    case SEMESTER_6A:
      return "(id_cours LIKE 'R6.%' or id_cours LIKE 'P6.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE6.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND id_cours NOT LIKE 'R6.B%'
              AND (type_seance = 'CM-APP' or type_seance = 'TD2' or type_seance = 'TPC' or type_seance = 'SAE-APP') ";
    case SEMESTER_6B:
      return "(id_cours LIKE 'R6.%' or id_cours LIKE 'P6.%' or id_cours in (select id_cours from seances where id_cours LIKE 'SAE6.%' and annee_scolaire = '".SELECTED_YEAR."'))
              AND id_cours NOT LIKE 'R6.A%'
              AND (type_seance = 'CM-APP' or type_seance = 'TD3' or type_seance = 'TPD' or type_seance = 'SAE-APP')
              AND NOT type_seance = 'TD2'
              AND NOT type_seance = 'TPC' ";
    default:
      throw new Exception("Invalid semester");
  }
}

function getFormation($semester) {
  switch ($semester) {
    case SEMESTER_1:
    case SEMESTER_2:
      return "BUT1";
    case SEMESTER_3:
    case SEMESTER_4:
      return "BUT2";
    case SEMESTER_3APP:
    case SEMESTER_4APP:
      return "BUT2-APP";
    case SEMESTER_5:
    case SEMESTER_6:
      return "BUT3";
    case SEMESTER_5A:
    case SEMESTER_6A:
    case SEMESTER_5B:
    case SEMESTER_6B:
      return "BUT3-APP";
    default:
      throw new Exception("Invalid semester");
  }
}

function getWeeks($db, $search) {
  $query = "SELECT DISTINCT num_sem
            FROM maquette WHERE " . $search . "
            AND annee_scolaire = '" . SELECTED_YEAR . "'
            ORDER BY num_sem";

  $stmt = $db->query($query);
  $weeks = $stmt->fetchAll(PDO::FETCH_COLUMN);
  return $weeks;
}

function getRessources($db, $search) {
  $query = "SELECT id_cours
            FROM seances
            WHERE " . $search . "
            GROUP BY id_cours
            ORDER BY id_cours";
  $stmt = $db->query($query);
  $ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $ressources;
}

function getServices($db, $search) {
  $query = "SELECT id_cours, type_seance, sum(duree_seance) as duree_seance
            FROM seances
            WHERE " . $search . "
            AND annee_scolaire = '" . SELECTED_YEAR . "'
            GROUP BY id_cours, type_seance";
  $stmt = $db->query($query);
  $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $services;
}

function buildTableRows($db, $ressources, $services, $list_week, $week_names, $formation, $semester) {
  $rows = "";

  foreach ($services as $service) {
    $services_lookup[$service['id_cours']][$service['type_seance']] = $service['duree_seance'];
  }

  foreach ($ressources as $ressource) {
    $id_cours = sanitize($ressource['id_cours']);

    $query = "SELECT type_seance
              FROM formation_groupe
              WHERE id_form = :id_form ";
    switch ($semester) {
      case SEMESTER_5A:
      case SEMESTER_6A:
        $query .= "AND NOT type_seance = 'TD3'
                  AND NOT type_seance = 'TPD' ";
        break;
      case SEMESTER_5B:
      case SEMESTER_6B:
        $query .= "AND NOT type_seance = 'TD2'
                  AND NOT type_seance = 'TPC' ";
        break;
    }
    $query .= "ORDER BY type_seance";

    $stmt = $db->prepare($query);
    $stmt->bindParam('id_form', $formation, PDO::PARAM_STR);
    $stmt->execute();
    $seances = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $rows .= "<tr><td rowspan='" . (count($seances) + 1) . "' class='w3-border'>$id_cours</td>";

    // Fetch all duree_semaine values for this ressource, formation, and list of weeks in one query
    $placeholders_semaines = implode(',', array_fill(0, count($list_week), '?'));
    $placeholders_seances = implode(',', array_fill(0, count($seances), '?'));
    $query = "SELECT num_sem, type_seance, duree_semaine
              FROM maquette
              WHERE id_cours = ?
              AND num_sem IN ($placeholders_semaines)
              AND type_seance IN ($placeholders_seances)";

    $stmt = $db->prepare($query);
    $params = array_merge([$id_cours], $list_week, $seances);

    foreach ($params as $key => $value) {
      $stmt->bindValue($key + 1, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
    $duree_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Index the results for easy lookup
    $duree_lookup = [];
    foreach ($duree_results as $row) {
      // if (isset($mutualisation[$id_cours][$row['type_seance']])) {
      //   $query = "SELECT num_sem, type_seance, duree_semaine
      //             FROM maquette
      //             WHERE id_cours = '$id_cours'
      //             AND num_sem = '{$row['num_sem']}'
      //             AND type_seance = '{$mutualisation[$id_cours][$row['type_seance']]}'";

      //   $stmt = $db->prepare($query);
      //   $stmt->execute();
      //   $duree_mutualisation = $stmt->fetch(PDO::FETCH_ASSOC);
      //   if ($duree_mutualisation !== false) {
      //     $row['duree_semaine'] = $duree_mutualisation;
      //   }
      // }

      $duree_lookup[$row['num_sem']][$row['type_seance']] = $row['duree_semaine'];
    }

    foreach ($seances as $seance) {
      $rows .= "<tr>";
      $rows .= "<td class='w3-border'>" . sanitize($seance) . "</td>";
      $sum_duree = 0;

      $current_week = isset($week_names[$list_week[0]]) ? $week_names[$list_week[0]] : null; // Check if the week exists in $week_names

      foreach ($list_week as $week) {
          $week_name = isset($week_names[$week]) ? $week_names[$week] : null; // Check if the week exists in $week_names

          if ($week_name === null) {
              $rows .= "<td class='w3-border w3-center w3-red'>Semaine inconnue</td>"; // Handle unknown weeks
              continue;
          }

          /*if (($current_week !== null && $week_name !== null) && ($current_week + 1) !== $week_name && $current_week !== $week_name) {
              $rows .= "<td class='w3-border w3-center w3-grey'></td>";
          }*/

          $current_week = $week_name;
          $duree = isset($duree_lookup[$week][$seance]) ? $duree_lookup[$week][$seance] : 0;

          if ($duree > 0) {
              $rows .= "<td class='w3-border'>" . sanitize($duree) . "</td>";
              $sum_duree += $duree;
          } else {
              $rows .= "<td class='w3-border'></td>";
          }
      }

      if ($services_lookup[$id_cours][$seance] > $sum_duree) {
        $color_total = 'w3-red';
      } else if ($services_lookup[$id_cours][$seance] < $sum_duree) {
        $color_total = 'w3-blue';
      } else {
        $color_total = 'w3-light-green';
      }
      $rows .= "<td class='w3-border $color_total'>" . sanitize($sum_duree) . " H</td>";
      $rows .= "</tr>";
    }
  }
  return $rows;
}

function generateTable($db, $semester) {
    global $week_names;

    try {
        $search = getSearchCondition($semester);
        $formation = getFormation($semester);
        $services = getServices($db, $search);
        $weeks = getWeeks($db, $search);

        $current_list_names = [];
        $list_week = [];

        $current_week = null;
        foreach ($weeks as $week) {
            $week_num = substr($week, 5, 2);
            $current_week_num = is_null($current_week) ? null : substr($current_week, 5, 2);

            if (($current_week_num + 1) !== (int)$week_num && $current_week !== $week && $current_week_num !== null) {
                $query = "SELECT num_sem
                          FROM semaines
                          WHERE num_sem > :week1
                          AND num_sem < :week2
                          ORDER BY num_sem";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':week1', $current_week, PDO::PARAM_STR);
                $stmt->bindParam(':week2', $week, PDO::PARAM_STR);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $empty_week) {
                    $current_list_names[] = "<th class='w3-border'>{$empty_week['num_sem']}</th>";
                    $list_week[] = $empty_week['num_sem'];
                }
            }
            $current_list_names[] = "<th class='w3-border'>$week</th>";
            $list_week[] = $week;
            $current_week = $week;
        }

        $ressources = getRessources($db, $search);
        $list = "<h2>Maquette des ressources pour le semestre $semester</h2>";
        $list .= "<div class='w3-responsive'><table class='w3-table w3-border w3-center w3-margin-bottom'>";
        $list .= "<thead class='w3-light-grey'><tr>
                    <th class='w3-border w3-centered'>Ressource</th>
                    <th class='w3-border'>Type</th>
                    " . implode('', $current_list_names) . "
                    <th class='w3-border'>Total</th>
                  </tr></thead><tbody>";

        $list .= buildTableRows($db, $ressources, $services, $list_week, $week_names, $formation, $semester);

        $list .= "</tbody></table></div>";

        return $list;

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile());
        return 'Erreur: Problème de base de données. Veuillez consulter les journaux du serveur.';
    } catch (Throwable $e) {
        error_log("General error: " . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile());
        return 'Erreur: Une erreur s\'est produite. Veuillez consulter les journaux du serveur.';
    }
}
