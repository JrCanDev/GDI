<?php
$db = require "$root/lib/pdo.php";

if (!isset($_SESSION)) {
  session_start();
}
define('SELECTED_YEAR', $_SESSION['annee']);

switch (GETPOST('action')) {
  case 'vue2':
    include "$root/controllers/maquette/vue2.controller.php";
    include "$root/views/maquette/vue2.view.php";
    break;

  case 'vue1':
  case null:
    include "$root/controllers/maquette/vue1.controller.php";
    include "$root/views/maquette/vue1.view.php";
    break;

  default:
    include "$root/views/404.php";
    break;
}