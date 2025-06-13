<?php
session_start();
// Permet d'activer l'affichage des erreurs
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

require_once dirname(__FILE__) . '/lib/project.lib.php';

if (GETPOST('debug') == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

require_once(dirname(__FILE__) . '/class/authClass.php');

if (isset($_POST['connect'])) {
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];
    $user = authClass::authenticate($uname, $psw);
    if ($user) // Ajuster le test en fonction des besoins
    {
        $_SESSION['mesgs']['confirm'][] = 'Connexion réussie ' . $user['prenom_ens'] . ' ' . $user['nom_ens'];
        $_SESSION['login'] = $user['nom_util'];
        $_SESSION['titulaire'] = $user['titulaire_ens'];
        $_SESSION['admin'] = $user['admin'];
        $_SESSION['user'] = $user;
    }
    else{
        $_SESSION['mesgs']['errors'][] = 'Identification impossible';
    }
}

header('Location:index.php');
