<?php
switch (GETPOST('action')) {
  case 'enseignant':
    include "$root/controllers/template/template.controller.php";
    include "$root/views/template/template.view.php";
    break;

  case null:
    $pageTitle = 'template';
    include "$root/views/template/index.view.php";
    break;

  default:
    include "$root/views/404.php";
    break;
}