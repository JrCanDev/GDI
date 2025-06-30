<?php
//Affichage de chaque groupes, et statistique minimales
function getTime($db, $semestre = '', $app = '', $typeCM = '', $typeTD = '', $typeTP = '') {
  $sqlHeuresApp = '';
  $heures = 0;
  $heuresSAE = 0;
  $heuresApp = 0;

  $sqlHeures = "select sum(duree_seance) as total_heures 
                FROM seances ";

  $sqlHeuresSAE = $sqlHeures;

  $sqlHeuresApp = "select sum(A1.duree_seance) as total_heures 
                  FROM seances A1
                  INNER JOIN seances A2 ON (A1.id_ens=A2.id_ens and A1.id_cours=A2.id_cours and A1.type_seance=A2.mutualisation) ";

  switch ($semestre) {
    case '1':
      $sqlHeures .= "WHERE id_cours like '_1.__' 
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

      $sqlHeuresSAE .= "WHERE id_cours like 'SAE1.%' ";
      break;

    case '2':
      $sqlHeures .= "WHERE id_cours like '_2.__' 
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

      $sqlHeuresSAE .= "WHERE id_cours like 'SAE2.%' ";
      break;

    case '3':
      if($app) {
        $sqlHeures .= "WHERE id_cours like '_3.__' 
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE A2.id_cours like '_3.__' 
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      } else {
        $sqlHeures .= "WHERE id_cours like '_3.__' 
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

        $sqlHeuresSAE .= "WHERE id_cours like 'SAE3.%' AND type_seance = 'SAE-FI' ";
      }
      break;

    case '4':
      if($app) {
        if ($app == 'A') {
          $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.A.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_4.__' or A2.id_cours like '_4.A.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        } else {
          $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.B.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_4.__' or A2.id_cours like '_4.B.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        }
      } else {
        if ($typeTP == 'TPD'){
          $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.B.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
  
          $sqlHeuresSAE .= "WHERE id_cours like 'SAE4.B.%' AND type_seance = 'SAE-FI' ";
        } else {
          $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.A.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

          $sqlHeuresSAE .= "WHERE id_cours like 'SAE4.A.%' AND type_seance = 'SAE-FI' ";
        }
      }
      break;

    case '5':
      if($app) {
        if ($app == 'A') {
          $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.A.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_5.__' or A2.id_cours like '_5.A.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        } else {
          $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.B.__') 
                        AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_5.__' or A2.id_cours like '_5.B.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        }
      } else {
        $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.A.__') 
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

        $sqlHeuresSAE .= "WHERE id_cours like 'SAE5.A.%' AND type_seance = 'SAE-FI' ";
      }
      break;

    case '6':
      if($app) {
        if ($app == 'A') {
          $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.A.__') 
                        AND type_seance='" . $typeTD . "' ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_6.__' or A2.id_cours like '_6.A.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        } else {
          $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.B.__') 
                        AND type_seance='" . $typeTD . "' ";

          $sqlHeuresApp .= "WHERE (A2.id_cours like '_6.__' or A2.id_cours like '_6.B.__') 
                            AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                            AND A2.mutualisation IS NOT NULL ";
        }
      } else {
        $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.A.__') 
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";

        $sqlHeuresSAE .= "WHERE id_cours like 'SAE6.A.%' AND type_seance = 'SAE-FI' ";
      }
      break;
  }
  $sqlHeuresSAE .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ";
  $sqlHeures .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ";
  $sqlHeuresApp .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ";

  try {
    $statement = $db->query($sqlHeures);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $heures = $result['total_heures'] ? sanitize(toFloat($result['total_heures'])) : 0;

    if($app) {
      $statement->closeCursor();
      $statement = $db->query($sqlHeuresApp);
      if ($statement !== false) {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
      }
      $heuresApp = isset($result['total_heures']) ? sanitize(toFloat($result['total_heures'])) : 0;
    } else {
      $statement->closeCursor();
      $statement = $db->query($sqlHeuresSAE);
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $heuresSAE = $result['total_heures'] ? sanitize(toFloat($result['total_heures'])) : 0;
    }

    $statement->closeCursor();
    $result = null;

    $total = $heures + $heuresApp;
    echo toFloat($total) . " H ressources + " . ($heuresSAE===0 ? '??' : toFloat($heuresSAE)) . " H SAE";
    return $app ? $total/30 : ($heuresSAE ? ($total+$heuresSAE)/30 : $total/30);
  } catch (Error | PDOException $e) {
    echo "Erreur lors de la recherche des heures";
    error_log($e->getMessage());
  }
}

$pageTitle = 'Statistiques';