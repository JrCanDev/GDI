<?php
require_once "$root/inc/log.php";
require_once "$root/controllers/motDePasse/index.controller.php";
require_once "$root/controllers/mail/index.controller.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; 

    $db = require "$root/lib/pdo.php";

    if ($email == "admin@admin"){ // si admin alors utiliser le mail du .env et nomer l'utilisateur "admin" 
        $email = $_ENV['MAIL_TEST']; 
        $username = "admin"; 
    } else { // sinon trouver le $username de $email 
        $stmt = $db->prepare("SELECT u.nom_util FROM utilisateurs u JOIN enseignants e ON u.id_ens = e.id_ens WHERE e.mail_ens = :email");
        $stmt->execute([':email' => $email]);
        $username = $stmt->fetchColumn();
    }

    if ($username) { // si $email appartient a un $username 
        // on créé un mot de passe temporaire -> token JWT
        $issuedAt = time();
        $expire = $issuedAt + 3600; // le token expire dans 1 heure

        $payload = [
            'iat'  => $issuedAt,
            'exp'  => $expire,
            'sub'  => $username,
            'email' => $email
        ];

        $token = \Firebase\JWT\JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        // On construit le lien de réinitialisation
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $resetLink = "$protocol://$host/index.php?page=resetMdp&token=$token"; // on passe le token en GET

        // On met le token en mot de passe 
        MAJMotDePasse($token, $username); 
        write_log(domain: 'addToken', username: $username);
        
        // Envoie du mail 
        sendMail("$root/controllers/mail/resetPassword.html", ["$root/img/logo.png"], $email, $token); 
    }

    // REMARQUE : même si le mail n'existe pas il faut tout de même envoyer le message de confirmation (sécurité) 
    $_SESSION['mesgs']['confirm'][] = "Si cette adresse existe, un e-mail a été envoyé pour réinitialiser votre mot de passe.";
    header('Location: index.php');
    exit();
}

include "$root/views/motDePasse/forgetPassword.view.php";
