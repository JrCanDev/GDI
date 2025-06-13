<?php
$db = require "$root/lib/pdo.php";

if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);

switch (GETPOST('action')) {
  case 'brutes':
    include "$root/controllers/stats/brutes.controller.php";
    include "$root/views/stats/brutes.view.php";
    break;

  case 'semestres':
    include "$root/controllers/stats/stats.controller.php";
    include "$root/views/stats/stats.view.php";
    break;

  case 'PNvsC':
    $BUT = sanitize(GETPOST('BUT'));
    include "$root/controllers/stats/PNvsC.controller.php";
    include "$root/views/stats/PNvsC.view.php";
    break;

  case 'groupes':
    $semester = sanitize(GETPOST('semester'));
    $app = sanitize(GETPOST('app'));
    $typeCM = sanitize(GETPOST('typeCM'));
    $typeTD = sanitize(GETPOST('typeTD'));
    $typeTP = sanitize(GETPOST('typeTP'));

    include "$root/controllers/stats/groupes.controller.php";
    include "$root/views/stats/groupes.view.php";
    break;

  case null:
    $pageTitle = 'Statistiques du BUT Informatique de Calais';
    include "$root/views/stats/index.view.php";
    break;
      
  default:
    include "$root/views/404.php";
    break;
}