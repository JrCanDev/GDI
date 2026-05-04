<?php 

/**
 * Récupère toutes les informations de l'utilisateur connecté en joignant
 * la table utilisateurs et enseignants.
 * 
 * @param string $username Le nom d'utilisateur (nom_util)
 * @return array|false Les données de l'utilisateur ou false si non trouvé
 */
function getUserInfo($username) {
    try {
        $db = require dirname(__FILE__) . '/../../lib/pdo.php';
        $sql = "SELECT * 
                FROM utilisateurs  
                LEFT JOIN enseignants ON utilisateurs.id_ens = enseignants.id_ens 
                WHERE utilisateurs.nom_util = :username";
        $statement = $db->prepare($sql);
        $statement->bindValue(':username', $username, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

$username = $_SESSION['login'] ?? null;
$userData = $username ? getUserInfo($username) : null;

include "$root/views/compte/index.view.php";
