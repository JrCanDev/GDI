<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once "$root/vendor/autoload.php";
require_once "$root/lib/project.lib.php";
$db = require "$root/lib/pdo.php";

$new_year = GETPOST('new_year');
$old_year = GETPOST('old_year');

try {
  $db->beginTransaction();

  $query = "INSERT INTO annee_scolaire (id_as) VALUES (:year)";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':year', $new_year);
  $result = $stmt->execute();
  $success = $stmt->rowCount();

  if ($success === 0 || $result === false) {
    throw new Exception("Année $new_year non insérée dans la base de données");
  }

  $query = "SELECT id_ens, id_cours, type_seance, duree_seance, mutualisation, commentaire_seance, paiement
            FROM seances
            WHERE annee_scolaire = :year";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':year', $old_year);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $stmt->closeCursor();

  if (!empty($result)) {
    $query = "INSERT INTO seances
              (annee_scolaire, id_ens, id_cours, type_seance, duree_seance, mutualisation, commentaire_seance, paiement)
              VALUES (:annee_scolaire, :id_ens, :id_cours, :type_seance, :duree_seance, :mutualisation, :commentaire_seance, :paiement)";
    $stmt = $db->prepare($query);

    foreach ($result as $row) {
      $id_ens = $row['id_ens'] === '' ? null : $row['id_ens'];
      $id_cours = $row['id_cours'] === '' ? null : $row['id_cours'];
      $type_seance = $row['type_seance'] === '' ? null : $row['type_seance'];
      $duree_seance = $row['duree_seance'] === '' ? null : $row['duree_seance'];
      $mutualisation = $row['mutualisation'] === '' ? null : $row['mutualisation'];
      $commentaire_seance = $row['commentaire_seance'] === '' ? null : $row['commentaire_seance'];
      $paiement = $row['paiement'] === '' ? null : $row['paiement'];

      $result = $stmt->execute([
        ':annee_scolaire' => $new_year,
        ':id_ens' => $id_ens,
        ':id_cours' => $id_cours,
        ':type_seance' => $type_seance,
        ':duree_seance' => $duree_seance,
        ':mutualisation' => $mutualisation,
        ':commentaire_seance' => $commentaire_seance,
        ':paiement' => $paiement
      ]);
      $success = $stmt->rowCount();

      if ($success === 0 || $result === false) {
        throw new Exception("Erreur lors de l'insertion d'une séance dans la base de données");
      }
    }
  }

  $first_year = substr($new_year, 0, 4);
  $second_year = substr($new_year, 5, 4);
  $date_august = new DateTime("$first_year-08-30");
  $date_december = new DateTime("$first_year-12-31");
  $august = $date_august->format("W");
  $december = $date_december->format("W");

  $query = "INSERT INTO semaines
            (num_sem)
            VALUES (:num_sem)";
  $stmt = $db->prepare($query);

  for ($week = $august; $week <= $december; $week++) {
    $result = $stmt->execute([':num_sem' => "$first_year-$week"]);
    $success = $stmt->rowCount();

    if ($success === 0 || $result === false) {
      throw new Exception("Erreur lors de l'insertion d'une semaine de $first_year dans la base de données");
    }
  }

  for ($week = 1; $week <= 27; $week++) {
    $week_num = str_pad($week, 2, "0", STR_PAD_LEFT);
    $result = $stmt->execute([':num_sem' => "$second_year-$week_num"]);
    $success = $stmt->rowCount();

    if ($success === 0 || $result === false) {
      throw new Exception("Erreur lors de l'insertion d'une semaine de $second_year dans la base de données");
    }
  }

  echo json_encode(['success' => 'valeurs copiées']);
  $db->commit();
} catch (Throwable $e) {
  $db->rollback();
  die(json_encode(['error' => 'Erreur: ' . $e->getMessage()]));
}
$db = null;