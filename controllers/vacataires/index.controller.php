<?php
$db = require "$root/lib/pdo.php";

if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);

if (!authClass::checkPriviledgeVacataire($_SESSION['user']['nom_util'])) {
  header('location: index');
}

switch (GETPOST('action')) {
  case null:
    function getEnseignantsArrayData($db = null) {
      
      try {
        $fields = array(
          "id_ens",
          "nom_ens",
          "prenom_ens",
          "titulaire_ens",
          "tel_ens",
          "mail_ens",
          "ville_ens",
          "commentaire_ens"
        );
        $sql = "SELECT distinct ".implode(", ", $fields)."
                FROM enseignants
                WHERE id_ens IN (
                  SELECT id_ens
                  FROM seances
                  WHERE annee_scolaire = '" . SELECTED_YEAR ."')
                AND id_ens NOT IN ('XX', 'XX2', 'SAE', '000')
                ORDER BY nom_ens, prenom_ens ";
        $statement = $db->query($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $list = array();
        foreach ($result as $row) {
          $id_ens = $row['id_ens'] ? sanitize($row['id_ens']) : null;
          $prenom_nom_ens = $row['prenom_ens'] && $row['nom_ens'] ? sanitize($row['prenom_ens'] . ' ' . $row['nom_ens']) : null;
          $tel_ens = $row['tel_ens'] ? sanitize($row['tel_ens']) : null;
          $mail_ens = $row['mail_ens'] ? sanitize($row['mail_ens']) : null;
          $ville_ens = $row['ville_ens'] ? sanitize($row['ville_ens']) : null;
          $commentaire_ens = $row['commentaire_ens'] ? sanitize($row['commentaire_ens']) : null;
          $titulaire_ens = $row['titulaire_ens'] ? sanitize($row['titulaire_ens']) : null;
          $titulaire_ens = $titulaire_ens === true ? 'Titulaire' : 'Vacataire';

          $list[] = [$id_ens, $prenom_nom_ens, $tel_ens, $mail_ens, $ville_ens, $commentaire_ens, $titulaire_ens];
        }
        $list = json_encode($list);
        echo $list;

        $statement->closeCursor();
      } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
      }
    }
    
    $pageTitle = 'Liste des vacataires - titulaires';
    include "$root/views/vacataires/index.view.php";
    break;

  default:
    include "$root/views/404.php";
    break;
}