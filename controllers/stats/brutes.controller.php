<?php
// Récupération de toutes les données de détails et création du tableau
function getDonneesBrutes($db = null) {
  $list = "";
  try {
    $fields = array(
      "id_ens",
      "nom_ens",
      "prenom_ens",
      "id_cours",
      "intitule_cours",
      "type_seance",
      "duree_seance",
      "mutualisation",
      "commentaire_seance",
      "paiement"
    );
    $sql = "SELECT distinct ".implode(", ", $fields)." 
            FROM details WHERE annee_scolaire = '" . SELECTED_YEAR . "' 
            ORDER BY id_cours, type_seance, id_ens";
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

      $list .= "<tr>
        <td class='w3-border'>
          <form method='GET'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_cours . ">
            <input type='hidden' name='action' value='enseignement'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 12px;'>
            <b><i>" . $id_cours . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>
          <form method='GET'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_cours . ">
            <input type='hidden' name='action' value='enseignement'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 12px;'>
            <b><i>" . $intitule_cours . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>
          <form method='GET'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_ens . ">
            <input type='hidden' name='action' value='enseignant'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 12px;'>
            <b><i>" . $id_ens . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>
          <form method='GET'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_ens . ">
            <input type='hidden' name='action' value='enseignant'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 12px;'>
            <b><i>" . $prenom_ens . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border'>
          <form method='GET'>
            <input type='hidden' name='page' value='services'>
            <input type='hidden' name='id' value=" . $id_ens . ">
            <input type='hidden' name='action' value='enseignant'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 12px;'>
            <b><i>" . $nom_ens . "</i></b>
            </button>
          </form>
        </td>
        <td class='w3-border' style='font-size: 12px;'>" . $type_seance . "</td>
        <td class='w3-border' style='font-size: 12px;'>" . $duree_seance . " H</td>
        <td class='w3-border' style='font-size: 12px;'>" . $mutualisation . "</td>
        <td class='w3-border' style='font-size: 12px;'>" . $paiement . "</td>
        <td class='w3-border' style='font-size: 12px;'>" . $commentaire_seance . "</td>
      </tr>\n";
    }
    echo $list;

    $statement->closeCursor();
  } catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
  }
}

$pageTitle = 'Données complètes en BD';
