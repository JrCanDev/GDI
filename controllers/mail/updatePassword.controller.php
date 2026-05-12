<?php
require_once "$root/controllers/mail/index.controller.php";

function sendUpdatePasswordMail(){
    global $root;

    try {
        // récupération du mail de l'user  
        $db = require "$root/lib/pdo.php";

        $stmt = $db->prepare("SELECT mail_ens FROM enseignants e JOIN utilisateurs u ON e.id_ens = u.id_ens WHERE u.nom_util = :login");
        $stmt->execute([':login' => $_SESSION['login']]);
        $userMail = $stmt->fetchColumn();

        // Si pas d'email et compte admin alors utiliser l'email de test du .env
        if (!$userMail && $_SESSION['login'] == 'admin_nom') {
            $userMail = $_ENV['MAIL_TEST']; 
        }

        sendMail("$root/controllers/mail/updatePassword.html", ["$root/img/logo.png"], $userMail);
    } catch (Exception $e) {
        $_SESSION['mesgs']['errors'][] = "Erreur : " . $e->getMessage();
    }
}