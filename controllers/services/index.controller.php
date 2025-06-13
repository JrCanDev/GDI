<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/vendor/autoload.php';
require_once $root . '/lib/security.lib.php';
require_once $root . '/lib/project.lib.php';

if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);


switch (GETPOST('action')) {
  case 'enseignant':
    include "$root/controllers/services/enseignant.controller.php";
    include "$root/views/services/enseignant.view.php";
    break;

  case 'enseignement':
    include "$root/controllers/services/enseignement.controller.php";
    include "$root/views/services/enseignement.view.php";
    break;

  case null:
    // Récupération des services par enseignants
    function getServicesByEnseignants() {
      try {
        $db = require $_SERVER['DOCUMENT_ROOT'] . '/lib/pdo.php';
        $fields = array(
          "E.id_ens as id_ens",
          "nom_ens",
          "prenom_ens",
        );
        $sql = "SELECT distinct ".implode(", ", $fields)." ";
        $sql .= "FROM enseignants E ";
        $sql .= "INNER JOIN seances A on E.id_ens=A.id_ens ";
        $sql .= "WHERE E.id_ens IN (SELECT id_ens FROM seances WHERE annee_scolaire = '" . SELECTED_YEAR . "' ) ";
        $sql .= "AND E.id_ens NOT IN ('0000') ";
        $sql .= "order by nom_ens ";
        $statement = $db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $list = array();
        foreach ($result as $row) {
          $list[] = [sanitize($row['id_ens']), sanitize($row['prenom_ens'] . ' ' . $row['nom_ens'])];
        }
        $list = json_encode($list);
        echo $list;

        $statement->closeCursor();
      } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
      }
    }
    
    // Récupération des services par enseignements
    function getServicesByEnseignements($period, $projects = false, $autre = '') {
      try {
        $db = require $_SERVER['DOCUMENT_ROOT'] . '/lib/pdo.php';
        $fields = array(
          "C.id_cours as id_cours",
          "C.intitule_cours as intitule_cours"
        );
        $sql = "SELECT distinct ".implode(", ", $fields)." ";
        $sql .= "FROM cours C ";
        $sql .= "INNER JOIN seances A on C.id_cours=A.id_cours ";

        if($projects) {
          $sql .= "WHERE (C.id_cours like 'P" . $period . ".%' ";
          $sql .= "OR C.id_cours like 'SAE" . $period . ".%') ";
        }
        else if ($autre !== '') {
          if ('STG-APP' === $autre) {
            $sql .= "WHERE (C.id_cours like 'Stg%' ";
            $sql .= "OR C.id_cours like 'App%') ";
          }
          else if ('EVT' === $autre) {
            $sql .= "WHERE C.id_cours like 'EVT%' ";
          }
        }
        else {
          $sql .= "WHERE C.id_cours like 'R" . $period . ".%' ";
        }
        $sql .= "AND A.id_ens NOT IN ('0000') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $sql .= "order by C.id_cours ";
        $statement = $db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $list = array();
        foreach ($result as $row) {
          $list[] = [sanitize($row['id_cours']), sanitize($row['id_cours'] . ' - ' . $row['intitule_cours'])];
        }
        $list = json_encode($list);
        echo $list;

        $statement->closeCursor();
      } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
      }
    }

    // Récupération des services par responsabilités
    function getServicesByResp() {
      try {
        $db = require $_SERVER['DOCUMENT_ROOT'] . '/lib/pdo.php';
        $fields = array(
          "E.nom_ens",
          "E.prenom_ens",
          "C.id_cours",
          "C.intitule_cours",
          "A.duree_seance",
          "E.id_ens"
        );
        $sql = "SELECT E.nom_ens, E.prenom_ens, C.id_cours, C.intitule_cours, A.duree_seance, E.id_ens ";
        $sql .= "FROM cours C ";
        $sql .= "INNER JOIN seances A on C.id_cours=A.id_cours ";
        $sql .= "INNER JOIN enseignants E on A.id_ens=E.id_ens ";
        $sql .= "WHERE C.id_cours like 'RESP%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $sql .= "order by C.id_cours ";
        $statement = $db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $list = "";
        foreach ($result as $row) {
          $list .= "<tr>
            <td class='w3-border'>RNT</td>
            <td class='w3-border'>" . sanitize($row['id_cours'] . ' - ' . $row['intitule_cours']) . "</td>
            <td class='w3-border'>
              <form method='GET'>
                <input type='hidden' name='page' value='services'>
                <input type='hidden' name='id' value=" . $row['id_ens'] . ">
                <input type='hidden' name='action' value='enseignant'>
                <button type='submit' class='w3-text-blue w3-left-align' 
                style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                <b>" . $row['prenom_ens'] . " " . $row['nom_ens'] . "</b>
                </button>
              </form>
            </td>
            <td class='w3-border'>" . sanitize($row['duree_seance']) . "</td>
          </tr>\n";
        }
        echo $list;

        $statement->closeCursor();
      } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
      }
    }
    
    $pageTitle = 'Liste des services';
    include "$root/views/services/index.view.php";
    break;
      
  default:
    include "$root/views/404.php";
    break;
}