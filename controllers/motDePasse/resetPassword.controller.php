<?php
require_once "$root/inc/log.php";
require_once "$root/controllers/motDePasse/index.controller.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$db = require "$root/lib/pdo.php";

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $_SESSION['mesgs']['errors'][] = "Token manquant";
    header('Location: index.php');
    exit();
} 
else {
    try {
        // Décodage du token JWT pour récupérer le nom d'utilisateur
        $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        $username = $decoded->sub;
        $email = $decoded->email;
    } catch (Exception $e) {
        $_SESSION['mesgs']['errors'][] = "Ce lien de réinitialisation est invalide ou a expiré.";
        header('Location: index.php');
        exit();
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // En GET (on ouvre la page $root/views/motDePasse/resetPassword.view.php): on vérifie si le lien est 
    // encore valide (le token)

    $stmt = $db->prepare("SELECT mdp FROM utilisateurs WHERE nom_util = :username");
    $stmt->execute([':username' => $username]);
    $currentMdp = $stmt->fetchColumn();

    if (!$currentMdp || $currentMdp !== md5($token)) {
        $_SESSION['mesgs']['errors'][] = "Ce lien de réinitialisation est invalide ou a expiré."; 
        header('Location: index.php');
        exit();
    }

    // Le token est valide : on le consomme en vidant le champ "mdp" en base
    $stmt = $db->prepare("UPDATE utilisateurs SET mdp = '' WHERE nom_util = :username");
    $stmt->execute([':username' => $username]);
    write_log(domain: 'removeToken', username: $username);

    // On autorise la session pour cet utilisateur
    $_SESSION['reset_authorized_user'] = $username;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // En POST (l'utilisateur a répondu au formulaire de $root/views/motDePasse/resetPassword.view.php) 
    if (empty($_SESSION['reset_authorized_user']) || $_SESSION['reset_authorized_user'] !== $username) {
        $_SESSION['mesgs']['errors'][] = "Session de réinitialisation invalide ou expirée.";
        header('Location: index.php');
        exit();
    }

    $newPwd = $_POST['new_password'] ?? '';
    $confirmPwd = $_POST['confirm_password'] ?? '';

    if ($newPwd !== $confirmPwd) {
        $_SESSION['mesgs']['errors'][] = "Les mots de passe ne correspondent pas";
    } else {
        // Mise à jour du mot de passe
        MAJMotDePasse($newPwd, $username); 
        write_log(domain: 'resetPassword', username: $username);
        unset($_SESSION['reset_authorized_user']); // on retire l'autorisation

        $_SESSION['mesgs']['confirm'][] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.";
        header('Location: index.php');
        exit();
    }
}

include "$root/views/motDePasse/resetPassword.view.php";
