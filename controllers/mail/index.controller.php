<?php
/**
 * Ce fichier envoie du HTML par mail. 
 * 
 * regarder les autre .html dans /controllers/mail pour avoir le détaille des 
 * mail envoyer 
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


/**
 * Envoie le fichier .html par mail au destinataire 
 * 
 * @param string $cheminHTML le chemin du fichier html a envoyer 
 * @param string $destEmail L'adresse mail du destinataire 
 */
function sendMail($cheminHTML, $destEmail){
    global $root;
    $mail = new PHPMailer(true);

    try {
        // --- CONFIGURATION SERVEUR ---
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = $_ENV['SMTP_AUTH'] === 'true' || $_ENV['SMTP_AUTH'] === true;
        $mail->Username   = $_ENV['SMTP_USERNAME'];
        $mail->Password   = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];
        $mail->CharSet    = 'UTF-8';

        // --- DESTINATAIRES ---
        $mail->setFrom('no-reply@gdi-system.com', 'GDI System');
        $mail->addAddress($destEmail); 
        
        // --- CONTENU ---
        $mail->isHTML(true); 
        $mail->Subject = 'Confirmation de changement de mot de passe';
        
        // Ajout de l'image en tant qu'image embarquée
        $mail->addEmbeddedImage("$root/img/logo.png", 'logo_gdi');
        
        // Chargement du contenu HTML depuis le fichier
        $htmlContent = file_get_contents($cheminHTML);
        
        // On remplace le chemin relatif par le CID
        $htmlContent = str_replace('./img/logo.png', 'cid:logo_gdi', $htmlContent);
        
        $mail->Body = $htmlContent;

        
        $mail->send();

        $_SESSION['mesgs']['confirm'][] = "Email envoyer :D (a supprimer ici on s'en blc)"; 
        return true;
    } catch (Exception $e) {
        // Log de l'erreur ou gestion selon les besoins
        $_SESSION['mesgs']['errors'][] = $mail->ErrorInfo;
        return false;
    }
}


if (isset($_POST['send_update_mail'])) {

    // récupération du mail de l'user  
    $db = require "$root/lib/pdo.php";
    $stmt = $db->prepare("SELECT mail_ens FROM enseignants e JOIN utilisateurs u ON e.id_ens = u.id_ens WHERE u.nom_util = :login");
    $stmt->execute([':login' => $_SESSION['login']]);
    $userMail = $stmt->fetchColumn();

    sendMail("$root/controllers/mail/updatePassword.html", $userMail);
}

include "$root/views/mail/index.view.php";
