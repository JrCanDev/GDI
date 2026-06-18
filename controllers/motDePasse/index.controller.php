<?php /**
* Gestion des mot de passe : modification du mot de passe où reset du mot de passe
*/


/**
 * Met à jour le mot de passe de l'utilisateur connecté.
 * 
 * @param string $newPassword Le nouveau mot de passe en clair
 * @param string $username Le nom de l'utilisateur où il faut changer le mot de passe (si vide alors utilisateur connecter)
 * @return bool True si la mise à jour a réussi, false sinon
 */
function MAJMotDePasse($newPassword, $username = NULL) {
    try {
        $db = require dirname(__FILE__) . '/../../lib/pdo.php';
        if (!$username){
            $username = $_SESSION['login'] ?? NULL;
        }

        if (!$username) { // si l'utilisateur n'est pas connecter 
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
