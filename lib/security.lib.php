<?php
session_start(); // Démarrage de la session
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once dirname(__FILE__) . '/../class/authClass.php';
$authorized = authClass::is_auth($_SESSION);
if (!$authorized) {
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $page = $_GET['page'] ?? null;
    
    // Pages accessibles sans être connecté
    $allowed_pages = ['motDePasseOublie', 'resetMdp'];

    if ($url !== "/" && $url !== "/index.php") {
        header('Location: /index.php');
        exit();
    }
    
    if (!in_array($page, $allowed_pages)) {
        include $_SERVER['DOCUMENT_ROOT'].'/login.php';
        exit();
    }
}
