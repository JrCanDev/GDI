<?php
/**
 * Système de log (basé sur Monolog)
 * 
 * Ce fichier gère l'écriture et le formatage des logs de l'application.
 * Le chemin du fichier log est géré par la variable globale $LOGFILE.
 * 
 * UTILISATION :
 * 
 * Connexion (Login) :
 * - write_log(domain: 'login');
 * 
 * Déconnexion (Logout) :
 * - write_log(domain: 'logout');
 * 
 * Ajout en base de données (addDatabase) :
 * - write_log(domain: 'addDatabase', table: 'nom_table', dataAfter: 'attribut : valeur_ajoutée');
 * 
 * Modification en base de données (updateDatabase) :
 * - write_log(domain: 'updateDatabase', table: 'nom_table', dataBefore: 'attribut : valeur_avant', dataAfter: 'attribut : valeur_après');
 * 
 * Suppression en base de données (removeDatabase) :
 * - write_log(domain: 'removeDatabase', table: 'nom_table', dataBefore: 'attribut : valeur_supprimée');
 * 
 * Erreur système ou SQL (error) :
 * - write_log(domain: 'error', message: 'Message d\'erreur');
 * 
 * Génération du jeton (addToken) :
 * - write_log(domain: 'addToken', username: 'nom_utilisateur');
 * 
 * Consommation du jeton (removeToken) :
 * - write_log(domain: 'removeToken', username: 'nom_utilisateur');
 * 
 * Réinitialisation du mot de passe (resetPassword) :
 * - write_log(domain: 'resetPassword', username: 'nom_utilisateur');
 * 
 * Mise à jour du mot de passe depuis le compte (updatePassword) :
 * - write_log(domain: 'updatePassword');
 */

$LOGFILE = dirname(__FILE__) . '/../log/log_GDI.log'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chargement de monolog 
if (!class_exists('Monolog\Logger')) {
    require_once dirname(__FILE__) . '/../vendor/autoload.php';
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;



/**
 * Écrit une entrée de log dans le fichier à l'aide de Monolog.
 * 
 * Format : [date heure] [domaine] [utilisateur]: l'information
 * Exemple : [01-01-2026 12:12:12] [login] [utilisateur]: l'utilisateur "utilisateur" s'est connecté
 * 
 * Le domaine doit être : 
 * - login : pour les connexions 
 * - logout : pour les déconnexions 
 * - updateDatabase : pour les modifications en bdd 
 * - removeDatabase : pour les suppressions en bdd 
 * - addDatabase : pour les ajouts en bdd 
 * - error : pour les erreurs 
 * - addToken : pour la génération d'un jeton de réinitialisation
 * - removeToken : pour la consommation d'un jeton de réinitialisation
 * - resetPassword : pour la réinitialisation du mot de passe suite à la consommation d'un jeton
 * - updatePassword : pour la mise à jour du mot de passe depuis le compte
 * 
 * @param string $domain Le domaine de l'action (qui sera utilisé comme nom de canal Monolog).
 * @param string|null $username L'utilisateur effectuant l'action (par défaut: utilisateur connecté, ou SYSTEM).
 * @param string|null $logFile Le chemin du fichier de log personnalisé (par défaut: valeur globale $LOGFILE).
 * @param string|null $table Le nom de la table affectée, ou message d'erreur.
 * @param mixed $dataBefore Les données avant modification.
 * @param mixed $dataAfter Les données après modification.
 * @param string|null $message Le message d'erreur spécifique (uniquement pour le domaine 'error').
 * @return bool True en cas de succès, false sinon.
 */
function write_log($domain, $username = null, $logFile = null, $table = null, $dataBefore = null, $dataAfter = null, $message = null) {
    // Si $logFile n'est pas précisé, on utilise la variable globale
    if ($logFile === null) {
        global $LOGFILE; 
        $logFile = $LOGFILE;
    }

    try {
        // Vérifications : 

        // Validation du domaine
        $allowed_domains = ['login', 'logout', 'updateDatabase', 'removeDatabase', 'addDatabase', 'error', 'addToken', 'removeToken', 'resetPassword', 'updatePassword'];
        if (!in_array($domain, $allowed_domains)) {
            throw new InvalidArgumentException("Le domaine de log '$domain' n'est pas autorisé.");
        }

        // Si updateDatabase alors $dataBefore et $dataAfter sont obligatoires 
        if ($domain === 'updateDatabase') {
            if ($dataBefore === null || $dataAfter === null) {
                throw new InvalidArgumentException("Pour le domaine 'updateDatabase', les données avant ('dataBefore') et après ('dataAfter') sont obligatoires.");
            }
        }

        // Si removeDatabase alors $dataBefore est obligatoire 
        if ($domain === 'removeDatabase') {
            if ($dataBefore === null) {
                throw new InvalidArgumentException("Pour le domaine 'removeDatabase', les données avant ('dataBefore') sont obligatoires.");
            }
        }

        // Si addDatabase alors $dataAfter est obligatoire 
        if ($domain === 'addDatabase') {
            if ($dataAfter === null) {
                throw new InvalidArgumentException("Pour le domaine 'addDatabase', les données après ('dataAfter') sont obligatoires.");
            }
        }

        // Si pas d'username alors on recherche l'utilisateur connecté. Si pas connecté alors SYSTEM
        if ($username === null) {
            $username = isset($_SESSION['user']['nom_util']) ? $_SESSION['user']['nom_util'] : 'SYSTEM';
        }


        // Conversion des tableaux associatifs en chaînes "attribut : donnée" pour le système de logs
        foreach ([&$dataBefore, &$dataAfter] as &$data) {
            if (is_array($data)) {
                $parts = [];
                foreach ($data as $col => $val) {
                    $parts[] = "$col : $val";
                }
                $data = implode(' | ', $parts);
            }
        }


        // Construction du message selon le domaine
        switch ($domain) {
            case 'login':
                $message = "l'utilisateur \"$username\" s'est connecté";
                break;

            case 'logout':
                $message = "l'utilisateur \"$username\" s'est déconnecté";
                break;

            case 'updateDatabase':
                $message = "l'utilisateur \"$username\" a modifié une donnée dans la table \"$table\". Données avant : \"$table => $dataBefore\" | Données après : \"$table => $dataAfter\"";
                break;

            case 'removeDatabase':
                $message = "l'utilisateur \"$username\" a supprimé une donnée de la table \"$table\". Données avant : \"$table => $dataBefore\"";
                break;

            case 'addDatabase':
                $message = "l'utilisateur \"$username\" a ajouté une donnée dans la table \"$table\". Données après : \"$table => $dataAfter\"";
                break;

            case 'addToken':
                $message = "un token de réinitialisation de mot de passe a été généré pour l'utilisateur \"$username\"";
                break;

            case 'removeToken':
                $message = "le token de réinitialisation de l'utilisateur \"$username\" a été consommé (champ mdp vidé)";
                break;

            case 'resetPassword':
                $message = "le mot de passe de l'utilisateur \"$username\" a été réinitialisé suite à la consommation du token";
                break;

            case 'updatePassword':
                $message = "l'utilisateur \"$username\" a mis à jour son mot de passe depuis son compte";
                break;

            case 'error':
                // pas de modification sur $message 
                break;
                
            default:
                $message = $table;
                break;
        }

        // Initialisation de Monolog
        $logger = new Logger($domain);
        $logger->setTimezone(new DateTimeZone('Europe/Paris'));

        // Formateur et Handler configurés pour intercepter tous les niveaux (DEBUG et +)
        // Remplacées automatiquement par Monolog :
        // - %datetime%       : Remplacé par la date et l'heure actuelles (formatées via le 2e argument "d-m-Y H:i:s")
        // - %channel%        : Remplacé par le nom du canal (ici la variable $domain donnée au constructeur new Logger)
        // - %extra.username% : Remplacé par le nom d'utilisateur (injecté via le processeur ci-dessous)
        // - %message%        : Remplacé par le texte du message de log passé dans la fonction $logger->log(...)
        $formatter = new LineFormatter("[%datetime%] [%channel%] [%extra.username%]: %message%\n", "d-m-Y H:i:s", false, true);
        $handler = (new StreamHandler($logFile, Logger::DEBUG))->setFormatter($formatter);
        $logger->pushHandler($handler);

        // Pour extra.username
        $logger->pushProcessor(function ($record) use ($username) {
            $record['extra']['username'] = $username;
            return $record;
        });

        // Écriture du log : niveau ERROR pour le domaine 'error', INFO pour le reste
        $logger->log($domain === 'error' ? Logger::ERROR : Logger::INFO, $message);
        return true;

    } catch (InvalidArgumentException $e) {
        throw $e;
    } catch (Throwable $e) {
        error_log("GDI Logger Error: " . $e->getMessage());
        $_SESSION['mesgs']['errors'][] = "Erreur lors de l'écriture du log : " . $e->getMessage();
        return false;
    }
}
