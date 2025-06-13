<?php
session_start(); // Démarrage de la session
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once dirname(__FILE__) . '/../class/authClass.php';
$authorized = authClass::is_auth($_SESSION);
if (!$authorized) {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($url !== "/" && $url !== "/index.php") {
        header('Location: /index.php');
        exit();
    }
    include $_SERVER['DOCUMENT_ROOT'].'/login.php';
    exit();
}
