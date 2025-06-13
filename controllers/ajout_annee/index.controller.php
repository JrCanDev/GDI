<?php
$db = require "$root/lib/pdo.php";

switch (GETPOST('action')) {
  case null:
    try {
      $list_year = [];
      $stmt = $db->query("SELECT id_as FROM annee_scolaire ORDER BY id_as DESC");
      $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($years as $year) {
        $list_year[] = $year['id_as'];
      }
    } catch (Throwable $e) {
      echo ''. $e->getMessage() .'';
    }
    $pageTitle = 'Créer/copier une année scolaire';
    include "$root/views/ajout_annee/index.view.php";
    break;
  
  default:
    include "$root/views/404.php";
    break;
}