<?php 
require_once "$root/controllers/motDePasse/index.controller.php";
require_once "$root/controllers/mail/updatePassword.controller.php";

// Traitement du formulaire de changement de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPwd = $_POST['new_password'] ?? '';
    $confirmPwd = $_POST['confirm_password'] ?? '';

    if (empty($newPwd) || empty($confirmPwd)) {
        $_SESSION['mesgs']['errors'][] = "Tous les champs sont obligatoires.";
    } elseif ($newPwd !== $confirmPwd) {
        $_SESSION['mesgs']['errors'][] = "Les mots de passe ne correspondent pas.";
    } else {
        if (MAJMotDePasse($newPwd)) {
            sendMail("$root/controllers/mail/updatePassword.html", ["$root/img/logo.png"]); // envoi du mail de confirmation 

            $_SESSION['mesgs']['confirm'][] = "Mot de passe mis à jour avec succès.";
            header('Location: index.php?page=compte');
            exit();
        } else {
            $_SESSION['mesgs']['errors'][] = "Erreur lors de la mise à jour du mot de passe.";
        }
    }
}

// Affichage de la vue
include "$root/views/motDePasse/updatePassword.view.php";
