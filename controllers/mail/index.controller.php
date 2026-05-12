<?php
/**
 * Ce fichier envoie du HTML par mail. 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


/**
 * Envoie un email au format HTML avec des images intégrées. REMARQUE : le titre du mail = balise <title> du html 
 *
 * @param string $cheminHTML Chemin vers le fichier HTML a envoyer
 * @param array  $imageTAB   Tableau de string contenant les chemins des images 
 * @param string $destEmail  Adresse email du destinataire
 * 
 * @return bool Retourne true si l'envoi a réussi, false sinon.
 */
function sendMail($cheminHTML, $imageTAB, $destEmail){
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
        
        // Chargement du contenu HTML depuis le fichier
        $htmlContent = file_get_contents($cheminHTML);

        // Extraction du titre du HTML (balise <tittle>) pour l'utiliser comme sujet du mail 
        if (preg_match('/<title>(.*?)<\/title>/is', $htmlContent, $matches)) {
            $mail->Subject = trim($matches[1]);
        } else {
            $mail->Subject = 'Notification GDI'; // Sujet par défaut si <title> absent
        }
        
        // Ajout d'image a importer : chemin de l'image = "$root/img/logo.png" --> son CID est "logo" 
        foreach ($imageTAB as $image) {
            $cid = pathinfo($image, PATHINFO_FILENAME);
            $mail->addEmbeddedImage($image, $cid); 
        }
        
        $mail->Body = $htmlContent;
        
        $mail->send();

        $_SESSION['mesgs']['confirm'][] = "Email envoyé"; 
        return true;
    } catch (Exception $e) {
        $_SESSION['mesgs']['errors'][] = "Erreur mail : " . $e->getMessage();
        return false;
    }
}
