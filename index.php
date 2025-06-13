<?php
require_once dirname(__FILE__) . '/lib/security.lib.php';
require_once dirname(__FILE__) . '/lib/project.lib.php';
include_once dirname(__FILE__) . '/vendor/autoload.php';

$root = $_SERVER['DOCUMENT_ROOT'];

switch (GETPOST('page')) {
  case 'services':
    include "$root/controllers/services/index.controller.php";
    break;

  case 'vaca':
    include "$root/controllers/vacataires/index.controller.php";
    break;

  case 'maquette':
    include "$root/controllers/maquette/index.controller.php";
    break;

  case 'stats':
    include "$root/controllers/stats/index.controller.php";
    break;

    case 'bd':
    include "$root/controllers/database/index.controller.php";
    break;

  case 'new_year':
    include "$root/controllers/ajout_annee/index.controller.php";
    break;

    case null:
    include "$root/controllers/accueil/index.controller.php";
    break;

    default:
    include "$root/views/404.php";
    break;
}
