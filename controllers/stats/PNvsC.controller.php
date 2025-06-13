<?php
//Affichage du comparatif PN vs Calais par année et parcours

if (!isset($_SESSION)) {
  session_start();
}
$selected_year = $_SESSION['annee'];

function getCompTab($db, $BUT, $aux = '') {
  global $selected_year;
  try {
      if($aux === "sae1" || $aux === "sae2") {
        $fields = array(
          "sum(volume) as volume",
        );
      } else {
        $fields = array(
          "id_cours",
          "volume",
        );
      }
    $sql = "SELECT ".implode(", ", $fields)." ";
    $sql .= "FROM volume_pn ";

    if ($aux === 'portfolio') {
      switch ($BUT[0]) {
        case '1' :
          $sql .= "WHERE id_cours like 'P1.%' or id_cours like 'P2.%' ";
          break;
        case '2' :
          $sql .= "WHERE id_cours like 'P3.%' or id_cours like 'P4.%' ";
          break;
        case '3' :
          $sql .= "WHERE id_cours like 'P5.%' or id_cours like 'P6.%' ";
          break;
      };
    } elseif ($aux === 'sae1') {
      switch ($BUT) {
        case '1' :
          $sql .= "WHERE id_cours like 'SAE1.__' ";
          break;
        case '2A' :
          $sql .= "WHERE id_cours like 'SAE3.__' or id_cours like 'SAE3.A.__' ";
          break;
        case '2B' :
          $sql .= "WHERE id_cours like 'SAE3.__' or id_cours like 'SAE3.B.__' ";
          break;
        case '3A' :
          $sql .= "WHERE id_cours like 'SAE5.__' or id_cours like 'SAE5.A.__' ";
          break;
      };
    } elseif ($aux === 'sae2') {
      switch ($BUT) {
        case '1' :
          $sql .= "WHERE id_cours like 'SAE2.__' ";
          break;
        case '2A' :
          $sql .= "WHERE id_cours like 'SAE4.__' or id_cours like 'SAE4.A.__' ";
          break;
        case '2B' :
          $sql .= "WHERE id_cours like 'SAE4.__' or id_cours like 'SAE4.B.__' ";
          break;
        case '3A' :
          $sql .= "WHERE id_cours like 'SAE6.__' or id_cours like 'SAE6.A.__' ";
          break;
      };
    } else {
      switch ($BUT) {
        case '1' :
          $sql .= "WHERE id_cours like 'R1.__' or id_cours like 'R2.__' ";
          break;
        case '2A' :
          $sql .= "WHERE id_cours like 'R3.__' or id_cours like 'R3.A.__' or id_cours like 'R4.__' or id_cours like 'R4.A.__'" ;
          break;
        case '2B' :
          $sql .= "WHERE id_cours like 'R3.__' or id_cours like 'R3.B.__' or id_cours like 'R4.__' or id_cours like 'R4.B.__' ";
          break;
        case '3A' :
          $sql .= "WHERE id_cours like 'R5.__' or id_cours like 'R5.A.__' or id_cours like 'R6.__' or id_cours like 'R6.A.__' ";
          break;
      };
    }

    $statement = $db->query($sql);

    if ($aux === 'sae1' || $aux === 'sae2') {
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      $volumePN = sanitize($result['volume']);

      $sql2 = "SELECT sum(duree_seance) as duree_seance ";
      $sql2 .= "FROM seances ";

      if ($aux === 'sae1') {
        switch ($BUT) {
          case '1' :
            $sql2 .= "WHERE id_cours like 'SAE1.__' ";
            break;
          case '2A' :
            $sql2 .= "WHERE id_cours like 'SAE3.A.__' ";
            break;
          case '2B' :
            $sql2 .= "WHERE id_cours like 'SAE3.B.__' ";
            break;
          case '3A' :
            $sql2 .= "WHERE id_cours like 'SAE5.A.__' ";
            break;
        };
      } else {
        switch ($BUT) {
          case '1' :
            $sql2 .= "WHERE id_cours like 'SAE2.__' ";
            break;
          case '2A' :
            $sql2 .= "WHERE id_cours like 'SAE4.A.__' ";
            break;
          case '2B' :
            $sql2 .= "WHERE id_cours like 'SAE4.B.__' ";
            break;
          case '3A' :
            $sql2 .= "WHERE id_cours like 'SAE6.A.__' ";
            break;
        };
      }
      $sql2 .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ";

      $statement = $db->query($sql2);
      $volumeCalais = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
      
      $semestreSAE = 'SAE semestre ';
      if ($aux === 'sae1') {
        switch ($BUT[0]) {
          case '1' :
            $semestreSAE .= '1';
            break;
          case '2' :
            $semestreSAE .= '3';
            break;
          case '3' :
            $semestreSAE .= '5';
            break;
        }
      } else {
        switch ($BUT[0]) {
          case '1' :
            $semestreSAE .= '2';
            break;
          case '2' :
            $semestreSAE .= '4';
            break;
          case '3' :
            $semestreSAE .= '6';
            break;
        }
      }

      $diff = number_format(toFloat($volumeCalais - $volumePN), 2);
      $pourcent = number_format(round(($volumeCalais*100)/$volumePN, 2), 2);
      $delta = mark($pourcent, $diff);

      echo "<tr>
        <td class='w3-border'>" . $semestreSAE . "</td>
        <td class='w3-border'>" . toFloat($volumePN) . " H</td>
        <td class='w3-border'>" . toFloat($volumeCalais) . " H</td>
        <td class='w3-border'>" . $delta . "</td>
      </tr>\n";

    } else {
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);

      $list = '';
      foreach ($result as $row) {
        $id_cours = sanitize($row['id_cours']);
        $volumePN = sanitize($row['volume']);

        $sql3 = "SELECT sum(duree_seance) as duree_seance ";
        $sql3 .= "FROM seances ";
        $sql3 .= "WHERE id_cours = :id_cours ";
        $sql3 .= $BUT == '2B' ?
          "AND (type_seance like 'CM' or type_seance like 'TD2' or type_seance like 'TPD')" :
          "AND (type_seance like 'CM' or type_seance like 'TD1' or type_seance like 'TPA')";
        $sql3 .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ";

        $statement = $db->prepare($sql3);
        $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
        $statement->execute();
        $volumeCalais = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $diff = number_format(toFloat($volumeCalais - $volumePN), 2);
        $pourcent = number_format(round(($volumeCalais*100)/$volumePN, 2), 2);
        $delta = mark($pourcent, $diff);

        $list .= "<tr>
          <td class='w3-border'>" . $id_cours . "</td>
          <td class='w3-border'>" . toFloat($volumePN) . " H</td>
          <td class='w3-border'>" . toFloat($volumeCalais) . " H</td>
          <td class='w3-border'>" . $delta . "</td>
        </tr>\n";
      }
      echo $list;

      $statement->closeCursor();
    }
  } catch (Error|Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
  }
}

function mark($pourcent,$diff) {
  if ($pourcent < 50) {
    return "<mark style='background-color:navy;'><span style='color: white;font-weight: bold;'>" . $pourcent . "%  |  " . -$diff . " H</span></mark>";
  } elseif ($pourcent < 75) {
    return "<mark style='background-color:blue;'><span style='color: white;'>" . $pourcent . "%  |  " . -$diff . " H</span></mark>";
  } elseif ($pourcent < 90) {
    return "<mark style='background-color:aqua;'>" . $pourcent . "%  |  " . -$diff . " H</mark>";
  } elseif ($pourcent < 100) {
    return $pourcent . "%  |  " . -$diff . " H";
  } elseif ($pourcent < 110) {
    return $pourcent . "%  |  " . $diff . " H";
  } elseif ($pourcent < 125) {
    return "<mark style='background-color:yellow;'>" . $pourcent . "%  |  " . $diff . " H</mark>";
  } elseif ($pourcent < 150) {
    return "<mark style='background-color:orange;'>" . $pourcent . "%  |  " . $diff . " H</mark>";
  } else {
    return "<mark style='background-color:red;'><span style='font-weight: bold;'>" . $pourcent . "%  |  " . $diff . " H</span></mark>";
  }
}

switch ($BUT) {
  case '1' :
    $pageTitle = "Comparaison heure étudiant en BUT1 - PN vs Calais";
    break;
  case '2A' :
    $pageTitle = "Comparaison heure étudiant en BUT2 (parcours A) - PN vs Calais";
    break;
  case '2B' :
    $pageTitle = "Comparaison heure étudiant en BUT2 (parcours B) - PN vs Calais";
    break;
  case '3A' :
    $pageTitle = "Comparaison heure étudiant en BUT3 (parcours A) - PN vs Calais";
    break;
};