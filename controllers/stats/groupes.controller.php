<?php
//Affichage des statistiques d'un groupe
$heures = 0;
$heuresApp = 0;
$total = 0;

$sqlCours = "SELECT C.id_cours, C.intitule_cours, A.type_seance, E.nom_ens, E.prenom_ens, A.duree_seance, A.mutualisation, A.id_ens 
              FROM seances A INNER JOIN cours C ON A.id_cours=C.id_cours
              INNER JOIN enseignants E ON A.id_ens=E.id_ens ";

$sqlHeures = "select sum(duree_seance) as total_heures 
              FROM seances ";

$sqlHeuresApp = "select sum(A1.duree_seance) as total_heures
                  FROM seances A1
                  INNER JOIN seances A2 ON (A1.id_ens=A2.id_ens and A1.id_cours=A2.id_cours and A1.type_seance=A2.mutualisation) ";

switch ($semester) {
  case '1':
    $sqlCours .= "WHERE A.id_cours like '_1.__'
                  AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

    $sqlHeures .= "WHERE id_cours like '_1.__'
                  AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    break;

  case '2':
    $sqlCours .= "WHERE A.id_cours like '_2.__'
                  AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

    $sqlHeures .= "WHERE id_cours like '_2.__'
                  AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    break;

  case '3':
    if($app) {
      $sqlCours .= "WHERE A.id_cours like '_3.__'
                    AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

      $sqlHeures .= "WHERE id_cours like '_3.__'
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

      $sqlHeuresApp .= "WHERE A2.id_cours like '_3.__'
                        AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                        AND A2.mutualisation IS NOT NULL ";
    } else {
      $sqlCours .= "WHERE A.id_cours like '_3.__'
                    AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

      $sqlHeures .= "WHERE id_cours like '_3.__'
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    }
    break;

  case '4':
    if($app) {
      if ($app == 'A') {
        $sqlCours .= "WHERE (A.id_cours like '_4.__' or A.id_cours like '_4.A.__') 
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.A.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_4.__' or A2.id_cours like '_4.A.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      } else {
        $sqlCours .= "WHERE A.id_cours like '_4.__' or A.id_cours like '_4.B.__')
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.B.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_4.__' or A2.id_cours like '_4.B.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      }
    } else {
      $sqlCours .= "WHERE (A.id_cours like '_4.__' or A.id_cours like '_4.A.__')
                    AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

      $sqlHeures .= "WHERE (id_cours like '_4.__' or id_cours like '_4.A.__')
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    }
    break;

  case '5':
    if($app) {
      if ($app == 'A') {
        $sqlCours .= "WHERE (A.id_cours like '_5.__' or A.id_cours like '_5.A.__')
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.A.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_5.__' or A2.id_cours like '_5.A.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      } else {
        $sqlCours .= "WHERE (A.id_cours like '_5.__' or A.id_cours like '_5.B.__')
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.B.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_5.__' or A2.id_cours like '_5.B.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      }
    } else {
      $sqlCours .= "WHERE (A.id_cours like '_5.__' or A.id_cours like '_5.A.__')
                    AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

      $sqlHeures .= "WHERE (id_cours like '_5.__' or id_cours like '_5.A.__')
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    }
    break;

  case '6':
    if($app) {
      if ($app == 'A') {
        $sqlCours .= "WHERE (A.id_cours like '_6.__' or A.id_cours like '_6.A.__')
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.A.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_6.__' or A2.id_cours like '_6.A.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      } else {
        $sqlCours .= "WHERE (A.id_cours like '_6.__' or A.id_cours like '_6.B.__')
                      AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "') ";

        $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.B.__')
                      AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "') ";

        $sqlHeuresApp .= "WHERE (A2.id_cours like '_6.__' or A2.id_cours like '_6.B.__')
                          AND (A2.type_seance='" . $typeCM . "' or A2.type_seance='" . $typeTD . "')
                          AND A2.mutualisation IS NOT NULL ";
      }
    } else {
      $sqlCours .= "WHERE (A.id_cours like '_6.__' or A.id_cours like '_6.A.__')
                    AND (A.type_seance='" . $typeCM . "' or A.type_seance='" . $typeTD . "' or A.type_seance='" . $typeTP . "') ";

      $sqlHeures .= "WHERE (id_cours like '_6.__' or id_cours like '_6.A.__')
                    AND (type_seance='" . $typeCM . "' or type_seance='" . $typeTD . "' or type_seance='" . $typeTP . "') ";
    }
    break;
}
$sqlCours .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ORDER BY A.id_cours, A.type_seance";
$sqlHeures .= "AND annee_scolaire = '" . SELECTED_YEAR . "'";
$sqlHeuresAPP .= "AND annee_scolaire = '" . SELECTED_YEAR . "'";

try {
  $statement = $db->query($sqlHeures);
  $result = $statement->fetch(PDO::FETCH_ASSOC);
  $heures = $result['total_heures'] ? sanitize(toFloat($result['total_heures'])) : 0;

  if($app) {
    $statement->closeCursor();
    $statement = $db->query($sqlHeuresApp);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $heuresApp = $result['total_heures'] ? sanitize(toFloat($result['total_heures'])) : 0;
  }

  $statement->closeCursor();
  $result = null;

  $total = $heures + $heuresApp;
} catch (PDOException $e) {
  echo 'Erreur' . $e->getMessage();
}

function getTab($db = null, $sql = '') {
  $list = "";
  try {
    $statement = $db->query($sql);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
      $id_ens = sanitize($row['id_ens']);
      $nom_ens = sanitize($row['nom_ens']);
      $prenom_ens = sanitize($row['prenom_ens']);
      $id_cours = sanitize($row['id_cours']);
      $intitule_cours = sanitize($row['intitule_cours']);
      $type_seance = sanitize($row['type_seance']);
      $duree_seance = sanitize(toFloat($row['duree_seance']));
      $mutualisation = sanitize($row['mutualisation']);
      $commentaire_seance = sanitize($row['commentaire_seance']);
      $paiement = sanitize($row['paiement']);

      $duree = $mutualisation ? $duree_seance . " H <mark style=\"background-color:red;\">mutualisé</mark> avec <b>" . $mutualisation . "</b>" : $duree_seance . " H";
      $list .= "<tr>
        <td class='w3-border'>
          <form method='GET' style='margin: 0;'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_cours . ">
            <input type='hidden' name='action' value='enseignement'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>" . $id_cours . " - " . $intitule_cours . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>" . $type_seance . "</td>
        <td class='w3-border'>
          <form method='GET' style='margin: 0;'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_ens . ">
            <input type='hidden' name='action' value='enseignant'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <b><i>" . $prenom_ens . " " . $nom_ens . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>" . $duree . "</td>
      </tr>\n";
    }
    echo $list;

    $statement->closeCursor();
  } catch (Error|Exception $e) {
      echo 'Erreur' . $e->getMessage();
  }
}
$designation = $app ? $typeTD . " (APP parcours " . $app . ")" : $typeTP;
$pageTitle = "Détails des ensigenements pour le " . $designation . " du semestre " . $semester;