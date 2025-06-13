<?php
class authClass
{
	public static function is_auth($current_session)
	{
		if (isset($current_session['user']) && !empty($current_session['user']))
			return true;
		return false;
	}

	public static function authenticate($username, $password)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';
			$fields = array(
				'nom_util',
				'e.id_ens',
				'nom_ens',
				'prenom_ens',
				'titulaire_ens',
				'admin'
			);
			$sql = "SELECT ".implode(', ', $fields)." 
					FROM utilisateurs as u join enseignants as e on u.id_ens = e.id_ens 
					WHERE nom_util = :username AND mdp = :passhash";
			$statement = $db->prepare($sql);
			$statement->bindValue(':username', $username, PDO::PARAM_STR);
			$statement->bindValue(':passhash', md5($password), PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		
		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}

	public static function register($username, $password, $firstname, $lastname)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';

			$fields = array(
				'nom_ens',
				'prenom_ens',
				'id_ens'
			);
			$sql = "SELECT ".implode(', ', $fields)."
					FROM enseignants 
					WHERE nom_ens ilike :lastname AND prenom_ens ilike :firstname";
			$statement = $db->prepare($sql);
			$statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);
			$statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			if ($result) {
			$exists = authClass::isAlreadyUsed($result['id_ens']);

				if (!$exists) {
					$sql = "INSERT INTO utilisateurs (nom_util, mdp, id_ens)
							VALUES (:username, :passhash, :idens)";
					$statement = $db->prepare($sql);
					$statement->bindValue(':username', $username, PDO::PARAM_STR);
					$statement->bindValue(':passhash', md5($password), PDO::PARAM_STR);
					$statement->bindValue(':idens', $result['id_ens'], PDO::PARAM_STR);
					$statement->execute();
					$result = $statement->fetch(PDO::FETCH_ASSOC);
					return true;
				}
			} else {
				$_SESSION['mesgs']['errors'][] = "Le professeur est déjà lié à un utilisateur";
			}
			return false;

		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}

	public static function isAlreadyUsed($id_ens)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';

			$fields = array(
				'id_ens'
			);
			$sql = "SELECT count(1)
					FROM utilisateurs
					WHERE id_ens = :id_ens ";
			$statement = $db->prepare($sql);
			$statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC)['count'];

			if ($result >= 1) {
				return true;
			}
			return false;

		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}

	public static function checkPriviledgeVacataire($username)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';

			$fields = array(
				"vacataire",
				"admin",
			);
			$sql = "SELECT distinct ".implode(", ", $fields)."
					FROM privileges_utilisateurs
					WHERE nom_util = :nom_util ";
			$statement = $db->prepare($sql);
			$statement->bindValue(':nom_util', $username, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			
			if ($result) {
				$admin = $result['admin'];
				$vac = $result['vacataire'];
				return $admin || $vac;
			}
			return false;

		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}

	public static function checkPriviledgeBudget($username)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';

			$fields = array(
				"budget",
				"admin",
			);
			$sql = "SELECT distinct ".implode(", ", $fields)."
					FROM privileges_utilisateurs
					WHERE nom_util = :nom_util ";
			$statement = $db->prepare($sql);
			$statement->bindValue(':nom_util', $username, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			
			if ($result) {
				$admin = $result['admin'];
				$budget = $result['budget'];
				return $admin || $budget;
			}
			return false;

		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}

	public static function checkPriviledgeDatabase($username)
	{
		try {
			$db = require dirname(__FILE__) . '/../lib/pdo.php';

			$fields = array(
				"admin",
			);
			$sql = "SELECT distinct ".implode(", ", $fields)."
					FROM privileges_utilisateurs
					WHERE nom_util = :nom_util ";
			$statement = $db->prepare($sql);
			$statement->bindValue(':nom_util', $username, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC)['admin'];
			return $result;

		} catch (Error|Exception $e) {
			echo $e->getMessage() . ' -> file: ' . $e->getFile() . ' - ligne: ' . $e->getLine();
		}
	}
}
