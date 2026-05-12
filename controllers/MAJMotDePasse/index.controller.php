<?php 
require_once "$root/controllers/mail/updatePassword.controller.php";

/**
 * Met à jour le mot de passe de l'utilisateur connecté.
 * 
 * @param string $newPassword Le nouveau mot de passe en clair
 * @return bool True si la mise à jour a réussi, false sinon
 */
function MAJMotDePasse($newPassword) {
    try {
        $db = require dirname(__FILE__) . '/../../lib/pdo.php';
        $username = $_SESSION['login'] ?? null;

        if (!$username) {
            return false;
        }

        $sql = "UPDATE utilisateurs SET mdp = :newMdp WHERE nom_util = :username";
        $statement = $db->prepare($sql);
        $statement->bindValue(':newMdp', md5($newPassword), PDO::PARAM_STR);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->execute();

        return true;
    } catch (Exception $e) {
        return false;
    }
}

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
            sendUpdatePasswordMail(); // envoi du mail de confirmation 

            $_SESSION['mesgs']['confirm'][] = "Mot de passe mis à jour avec succès.";
            header('Location: index.php?page=compte');
            exit();
        } else {
            $_SESSION['mesgs']['errors'][] = "Erreur lors de la mise à jour du mot de passe.";
        }
    }
}

// Affichage de la vue
include "$root/views/MAJMotDePasse/index.view.php";
