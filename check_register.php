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

if (isset($_POST['register'])) {
    $uname = GETPOST('uname');
    $psw = GETPOST('psw');
    $firstname = GETPOST('fname');
    $lastname = GETPOST('lname');
    $userCreated = authClass::register($uname, $psw, $firstname, $lastname);
    if ($userCreated) // Ajuster le test en fonction des besoins
    {
        $_SESSION['mesgs']['confirm'][] = 'Enregistrement réussie';
        header('Location:login.php');
    }
    else{
        $_SESSION['mesgs']['errors'][] = 'Identification impossible';
        header('Location:register.php');
    }
}