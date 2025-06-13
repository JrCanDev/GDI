<?php
$id_cours = GETPOST('id');
$intitule = '';
$resume = 0;

$db = require "$root/lib/pdo.php";

//Récupération des informations de l'enseignement
try {
    $fields = array(
        "intitule_cours",
    );
    $sql = "SELECT ".implode(", ", $fields)." ";
    $sql .= "FROM details ";
    $sql .= "WHERE id_cours = :id_cours AND annee_scolaire = '" . SELECTED_YEAR . "' ";
    $statement = $db->prepare($sql);
    $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    $intitule = $result['intitule_cours'];

    $statement->closeCursor();
    $result = null;
} catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
}

//Durée de chaque ressources
try {
    $fields = array(
        "sum(duree_seance) as duree_seance",
    );
    $sql = "SELECT ".implode(", ", $fields)." ";
    $sql .= "FROM details ";
    $sql .= "WHERE id_cours = :id_cours AND annee_scolaire = '" . SELECTED_YEAR . "' ";

    $statement = $db->prepare($sql);
    $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $resume = $result['duree_seance'] ? sanitize(toFloat($result['duree_seance'])) : 0;

    $statement->closeCursor();
    $result = null;
} catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
}

//Tableau d'nformations de chaque séances
function getServices($id) {
    try {
        $db = require $_SERVER['DOCUMENT_ROOT'] . '/lib/pdo.php';
        $fields = array(
            "type_seance",
            "prenom_ens",
            "nom_ens",
            "id_ens",
            "duree_seance",
            "mutualisation",
            "commentaire_seance",
        );
        $sql = "SELECT ".implode(", ", $fields)." ";
        $sql .= "FROM details ";
        $sql .= "WHERE id_cours = :id_cours AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $sql .= "ORDER BY type_seance";
        $statement = $db->prepare($sql);
        $statement->bindValue(':id_cours', $id, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        
        $list = "";
        foreach ($result as $row) {
            $type_seance = sanitize($row['type_seance']);
            $id_ens = sanitize($row['id_ens']);
            $enseignant = sanitize($row['prenom_ens'] . ' ' . $row['nom_ens']);
            $duree_seance = sanitize(toFloat($row['duree_seance']));
            $mutualisation = sanitize($row['mutualisation']);
            $commentaire_seance = sanitize($row['commentaire_seance']);

            $list .= "<tr>
                <td class='w3-border'>" . $type_seance . "</td>
                <td class='w3-border'>
                    <form method='GET'>
                        <input type='hidden' name='page' value='services'>
                        <input type='hidden' name='id' value=" . $id_ens . ">
                        <input type='hidden' name='action' value='enseignant'>
                        <button type='submit' class='w3-text-blue w3-left-align' 
                        style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                        <b>" . $enseignant . "</b>
                        </button>
                    </form>
                </td>
                <td class='w3-border'>" . $duree_seance . " H</td>
                <td class='w3-border'>" . $commentaire_seance . "</td>
            </tr>\n";
        }
        echo $list;

        $statement->closeCursor();
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

$pageTitle = $id_cours . ' - ' . $intitule;

