<?php
$id_ens = GETPOST('id');
$prenom = '';
$nom = '';
$titulaire = false;
$TP_enseignant = 0;
$TD_enseignant = 0;
$CM_enseignant = 0;
$SAE_enseignant = 0;
$EVT_enseignant = 0;
$RNT_enseignant = 0;
$Hors_quota_enseignant = 0;

$db = require "$root/lib/pdo.php";

//Récupération des informations de l'enseignant
try {
    $fields = array(
        "nom_ens",
        "prenom_ens",
        "titulaire_ens"
    );
    $query = "SELECT ".implode(", ", $fields)."
            FROM enseignants
            WHERE id_ens = :id_ens ";
    $statement = $db->prepare($query);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $prenom = sanitize($result['prenom_ens']);
    $nom = sanitize($result['nom_ens']);
    $titulaire = sanitize($result['titulaire_ens']);

    $statement->closeCursor();
    $result = null;
} catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
}

//Résumé
//Heure tableau Ressources et portfolio
try {
    $fields = array(
        "sum(duree_totale) as total",
    );
    $query = "SELECT ".implode(", ", $fields)."
            FROM tps_par_enseignant_par_type
            WHERE id_ens = :id_ens ";
    $query1 = "AND type_seance like 'TP%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
    $query2 = "AND type_seance like 'TD%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";

    $query3 .= "SELECT duree_totale as total
                FROM tps_par_enseignant_par_type
                WHERE id_ens = :id_ens
                AND type_seance like 'CM%'
                AND annee_scolaire = '" . SELECTED_YEAR . "' ";

    $statement = $db->prepare($query . $query1);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $TP_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $statement = $db->prepare($query . $query2);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $TD_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $statement = $db->prepare($query3);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $CM_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $result = null;
} catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
}


//Heures tableau Autres
try {
    $fields = array(
        "sum(duree_totale) as total",
    );
    $query = "SELECT ".implode(", ", $fields)."
            FROM tps_par_enseignant_par_type
            WHERE id_ens = :id_ens ";
    $query1 = "AND type_seance like 'RNT' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
    $query2 = "AND type_seance like 'SAE%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
    $query3 = "AND type_seance like 'EVT' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
    $query4 = "AND type_seance like 'Hors-quota' AND annee_scolaire = '" . SELECTED_YEAR . "' ";


    $statement = $db->prepare($query . $query1);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $RNT_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $statement = $db->prepare($query . $query2);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $SAE_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $statement = $db->prepare($query . $query3);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $EVT_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $statement = $db->prepare($query . $query4);
    $statement->bindValue(':id_ens', $id_ens, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $Hors_quota_enseignant = $result['total'] ? sanitize(toFloat($result['total'])) : 0;

    $statement->closeCursor();
    $result = null;
} catch (Error|Exception $e) {
    echo 'Erreur' . $e->getMessage();
}

//Détails
//Tableau RESP, STG
function tabRNT($db_p, $id_ens_p) {
    try {
        $fields = array(
            "id_cours",
            "type_seance",
            "intitule_cours",
            "duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM details ";
        $query .= "WHERE id_ens = :id_ens ";
        $query1 = $query . "AND id_cours like 'RESP%' ";
        $query2 = $query . "AND id_cours like 'Stg%' ";
        $query1 .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ORDER BY id_cours, type_seance";
        $query2 .= "AND annee_scolaire = '" . SELECTED_YEAR . "' ORDER BY id_cours, type_seance";

        //RESP
        $statement = $db_p->prepare($query1);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>Responsabilités</th></tr></thead>";
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);

                echo "<tbody><tr><td class='w3-border'>
                <form method='GET'>
                    <input type='hidden' name='page' value='services'>
                    <input type='hidden' name='id' value='" . $id_cours . "'>
                    <input type='hidden' name='action' value='enseignement'>
                    <button type='submit' class='w3-text-blue w3-left-align' 
                    style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                    <b>" . $id_cours . " - " . $intitule_cours . "</b>
                    </button>
                </form></td>
                <td class='w3-border'>" . toFloat($duree_seance) . "H</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        //STG
        $statement->closeCursor();
        $statement = $db_p->prepare($query2);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<table class='w3-table w3-bordered w3-border'><thead class='w3-light-gray'><tr><th colspan=2>Suivi de stage</th></tr></thead>";
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);

                echo "<tbody><tr><td class='w3-border'>
                <form method='GET'>
                    <input type='hidden' name='page' value='services'>
                    <input type='hidden' name='id' value='" . $id_cours . "'>
                    <input type='hidden' name='action' value='enseignement'>
                    <button type='submit' class='w3-text-blue w3-left-align' 
                    style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                    <b>" . $id_cours . " - " . $intitule_cours . "</b>
                    </button>
                </form></td>
                <td class='w3-border'>" . toFloat($duree_seance) . "H</td></tr>";
            }
            echo "</tbody></table>";
        }

        $statement->closeCursor();
        $result = null;
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau APP
function tabHQ($db_p, $id_ens_p) {
    try {
        $fields = array(
            "id_cours",
            "type_seance",
            "intitule_cours",
            "duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM details ";
        $query .= "WHERE id_ens = :id_ens ";
        $query .= "AND id_cours like 'App%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $query .= "ORDER BY id_cours, type_seance";

        $statement = $db_p->prepare($query);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>Suivi d'apprentis</th></tr></thead>";
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);

                echo "<tbody><tr><td class='w3-border'>
                <form method='GET'>
                    <input type='hidden' name='page' value='services'>
                    <input type='hidden' name='id' value='" . $id_cours . "'>
                    <input type='hidden' name='action' value='enseignement'>
                    <button type='submit' class='w3-text-blue w3-left-align' 
                    style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                    <b>" . $id_cours . " - " . $intitule_cours . "</b>
                    </button>
                </form></td>
                <td class='w3-border'>" . toFloat($duree_seance) . "H</td></tr>";
            }
            echo "</tbody></table>";
        }

        $statement->closeCursor();
        $result = null;
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau Ressources et portfolio
function tabRP($db_p, $id_ens_p, $period) {
    try {
        $previousId = '';

        $fields = array(
            "id_cours",
            "intitule_cours",
            "type_seance",
            "duree_seance",
            "mutualisation",
            "commentaire_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM details ";
        $query .= "WHERE id_ens = :id_ens ";
        $query .= "AND (id_cours like 'R" . $period . ".%' ";
        $query .= "OR id_cours like 'P" . $period . ".%') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $query .= "ORDER BY LEFT(id_cours, 1) DESC, id_cours, type_seance";

        $statement = $db_p->prepare($query);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $type_seance = sanitize($row['type_seance']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);
                $mutualisation = sanitize($row['mutualisation']);
                $commentaire_seance = sanitize($row['commentaire_seance']);
                
                if ($previousId !== $id_cours) {
                    if ($previousId !== ""){
                        echo "</tbody></table>";
                    }

                    echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=3>
                    <form method='GET'>
                        <input type='hidden' name='page' value='services'>
                        <input type='hidden' name='id' value='" . $id_cours . "'>
                        <input type='hidden' name='action' value='enseignement'>
                        <button type='submit' class='w3-text-blue w3-left-align w3-light-gray w3-large' 
                        style='cursor: pointer; border: none; font-size: 13px;'>
                        <i><b>" . $id_cours . " - " . $intitule_cours . "</b></i>
                        </button>
                    </form></th></tr></thead><tbody>";
                    $previousId = $id_cours;
                }

                if ($mutualisation) {
                    echo "<tr>
                    <td class='w3-border'>" . $type_seance . " - <strong><span style='color:red;'>mutualisé avec" . $mutualisation . "</td>
                    <td class='w3-border'>" . toFloat($duree_seance) . "H</td>";
                    if ($commentaire_seance) {
                        echo "<td class='w3-border'>" . $commentaire_seance . "</td>";
                    }
                    echo "</tr>";
                } else {
                    echo "<tr>
                    <td class='w3-border'>" . $type_seance . "</td>
                    <td class='w3-border'>" . toFloat($duree_seance) . "H</td>";
                    if ($commentaire_seance) {
                        echo "<td class='w3-border'>" . $commentaire_seance . "</td>";
                    }
                    else {
                        echo "<td class='w3-border'></td>";
                    }
                    echo "</tr>";
                }
            }
            if ($previousId !== ""){
                echo "</tbody></table>";
            }
        }

        $statement->closeCursor();
        $result = null;
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau SAE
function tabSAE($db_p, $id_ens_p) {
    try {
        $fields = array(
            "id_cours",
            "type_seance",
            "intitule_cours",
            "duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM details ";
        $query .= "WHERE id_ens = :id_ens ";
        $query .= "AND id_cours like 'SAE%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $query .= "ORDER BY id_cours, type_seance";

        $statement = $db_p->prepare($query);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>Encadrement de SAE</th></tr></thead>";
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);

                echo "<tbody><tr><td class='w3-border'>
                <form method='GET'>
                    <input type='hidden' name='page' value='services'>
                    <input type='hidden' name='id' value='" . $id_cours . "'>
                    <input type='hidden' name='action' value='enseignement'>
                    <button type='submit' class='w3-text-blue w3-left-align' 
                    style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                    <b>" . $id_cours . " - " . $intitule_cours . "</b>
                    </button>
                </form></td>
                <td class='w3-border'>" . toFloat($duree_seance) . "H</td></tr>";
            }
            echo "</tbody></table>";
        }

        $statement->closeCursor();
        $result = null;
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau EVT
function tabEVT($db_p, $id_ens_p) {
    try {
        $fields = array(
            "id_cours",
            "type_seance",
            "intitule_cours",
            "duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM details ";
        $query .= "WHERE id_ens = :id_ens ";
        $query .= "AND id_cours like 'EVT%' AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $query .= "ORDER BY id_cours, type_seance";

        $statement = $db_p->prepare($query);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>Suivi d'apprentis</th></tr></thead>";
            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
                $intitule_cours = sanitize($row['intitule_cours']);
                $duree_seance = sanitize($row['duree_seance']);

                echo "<tbody><tr><td class='w3-border'>
                <form method='GET'>
                    <input type='hidden' name='page' value='services'>
                    <input type='hidden' name='id' value='" . $id_cours . "'>
                    <input type='hidden' name='action' value='enseignement'>
                    <button type='submit' class='w3-text-blue w3-left-align' 
                    style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                    <b>" . $id_cours . " - " . $intitule_cours . "</b>
                    </button>
                </form></td>
                <td class='w3-border'>" . toFloat($duree_seance) . "H</td></tr>";
            }
            echo "</tbody></table>";
        }

        $statement->closeCursor();
        $result = null;
    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Dossier vacataire
//Tableau des heures de but1 - vacataires
function tabBUT1($db_p, $id_ens_p) {
    $TP = 0;
    $TD = 0;
    $CM = 0;
    $SAE = 0;
    $total = 0;

    try {
        $fields = array(
            "sum(duree_seance) as duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM seances ";
        $query .= "WHERE id_ens = :id_ens ";
        $query .= "AND (id_cours like 'R1.%' ";
        $query .= "OR id_cours like 'R2.%' ";
        $query .= "OR id_cours like 'P1.%' ";
        $query .= "OR id_cours like 'P2.%') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
        $query1 = $query . "AND type_seance like 'CM%' ";
        $query2 = $query . "AND type_seance like 'TD%' ";
        $query3 = $query . "AND type_seance like 'TP%' ";
        $query4 = "SELECT ".implode(", ", $fields)." FROM seances where id_ens = :id_ens AND annee_scolaire = '" . SELECTED_YEAR . "'  AND (id_cours like 'SAE1%' or id_cours like 'SAE2%') AND type_seance like 'SAE%' ";
        
        $statement = $db_p->prepare($query1);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $statement = $db_p->prepare($query2);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $statement = $db_p->prepare($query3);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $statement = $db_p->prepare($query4);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
        $total = toFloat($total);
        if($total > 0) {
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>BUT1 - " . toFloat($total) . " H eqTD</th></tr></thead><tbody>";
            
            $TP = 0;
            $TD = 0;
            $CM = 0;
            $SAE = 0;
            $total = 0;
            
            $fields = array(
                "distinct(id_cours)",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens ";
            $query .= "AND (id_cours like 'R1.%' ";
            $query .= "OR id_cours like 'R2.%' ";
            $query .= "OR id_cours like 'P1.%' ";
            $query .= "OR id_cours like 'P2.%' ";
            $query .= "OR id_cours like 'SAE1%' ";
            $query .= "OR id_cours like 'SAE2%') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            $query .= "ORDER BY id_cours ";

            $statement = $db_p->prepare($query);
            $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            $statement->closeCursor();

            $fields = array(
                "sum(duree_seance) as duree_seance",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            $query1 = $query . "AND id_cours = :id_cours AND type_seance like 'CM%' ";
            $query2 = $query . "AND id_cours = :id_cours AND type_seance like 'TD%' ";
            $query3 = $query . "AND id_cours = :id_cours AND type_seance like 'TP%' ";
            $query4 = $query . "AND id_cours = :id_cours AND type_seance like 'SAE%' ";

            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
            
                $statement = $db_p->prepare($query1);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query2);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query3);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query4);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

                $statement->closeCursor();
                $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
                $total = toFloat($total);

                if($total > 0) {
                    echo "<tr>
                        <td class='w3-border'><i class='fa fa-arrow-right'></i> " . $id_cours . "</td>
                        <td class='w3-border'>" . $total . " H eqTD</td>
                    </tr>";
                }
            }
            echo "</tbody></table>";
        }
        $result = null;

    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau des heures de but2 - vacataires
function tabBUT2($db_p, $id_ens_p, $App = false) {
    $TP = 0;
    $TD = 0;
    $CM = 0;
    $SAE = 0;
    $total = 0;
    $query1 = null;
    $query2 = null;
    $query3 = null;
    $query4 = null;

    try {
        $fields = array(
            "sum(duree_seance) as duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM seances ";
        $query .= "WHERE id_ens = :id_ens AND annee_scolaire = '" . SELECTED_YEAR . "' ";

        if($App) {
            $query .= "AND (id_cours like 'R3.%' ";
            $query .= "OR id_cours like 'R4.%' ";
            $query .= "OR id_cours like 'P3.%' ";
            $query .= "OR id_cours like 'P4.%') ";
            $query1 = $query . "AND type_seance like 'CM-APP' ";
            $query2 = $query . "AND type_seance like 'TD3' ";
            $query4 = "SELECT ".implode(", ", $fields)." 
                FROM seances 
                WHERE id_ens = :id_ens
                AND annee_scolaire = '" . SELECTED_YEAR . "'  
                AND (id_cours like 'SAE3%' or id_cours like 'SAE4%') 
                AND type_seance like 'SAE-APP' ";
        } else {
            $query .= "AND (id_cours like 'R3.%' ";
            $query .= "OR id_cours like 'R4.%' ";
            $query .= "OR id_cours like 'P3.%' ";
            $query .= "OR id_cours like 'P4.%') ";
            $query1 = $query . "AND type_seance like 'CM' ";
            $query2 = $query . "AND (type_seance like 'TD1' ";
            $query2 .= "OR type_seance like 'TD2') ";
            $query3 = $query . "AND (type_seance like 'TPA' ";
            $query3 .= "OR type_seance like 'TPB' ";
            $query3 .= "OR type_seance like 'TPC' ";
            $query3 .= "OR type_seance like 'TPD') ";
            $query4 = "SELECT ".implode(", ", $fields)." 
                FROM seances 
                WHERE id_ens = :id_ens
                AND annee_scolaire = '" . SELECTED_YEAR . "'  
                AND (id_cours like 'SAE3%' or id_cours like 'SAE4%') 
                AND type_seance like 'SAE-FI' ";
        }
        
        $statement = $db_p->prepare($query1);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $statement = $db_p->prepare($query2);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        if (!$App) {
            $statement->closeCursor();
            $statement = $db_p->prepare($query3);
            $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
            $statement->execute();
            $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        }

        $statement->closeCursor();
        $statement = $db_p->prepare($query4);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
        $total = toFloat($total);
        if($total > 0) {
            $title = $App ? "BUT2 APP - " . toFloat($total) : "BUT2 FI - " . toFloat($total) . " H eqTD";
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>" . $title . " H eqTD</th></tr></thead><tbody>";
            
            $TP = 0;
            $TD = 0;
            $CM = 0;
            $SAE = 0;
            $total = 0;
            
            $fields = array(
                "distinct(id_cours)",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens ";
            $query .= "AND (id_cours like 'R3.%' ";
            $query .= "OR id_cours like 'R4.%' ";
            $query .= "OR id_cours like 'P3.%' ";
            $query .= "OR id_cours like 'P4.%' ";
            $query .= "OR id_cours like 'SAE3%' ";
            $query .= "OR id_cours like 'SAE4%') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            $query .= "ORDER BY id_cours ";

            $statement = $db_p->prepare($query);
            $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
            $statement->closeCursor();

            $fields = array(
                "sum(duree_seance) as duree_seance",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens ";
            $query .= "AND id_cours = :id_cours AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            if ($App) {
                $query1 = $query . "AND type_seance like 'CM-APP' ";
                $query2 = $query . "AND type_seance like 'TD3' ";
                $query4 = $query . "AND type_seance like 'SAE-APP' ";
            } else {
                $query1 = $query . "AND type_seance like 'CM%' ";
                $query2 = $query . "AND (type_seance like 'TD1' OR type_seance like 'TD2') ";
                $query3 = $query . "AND (type_seance like 'TPA' OR type_seance like 'TPB' OR type_seance like 'TPC' OR type_seance like 'TPD') ";
                $query4 = $query . "AND type_seance like 'SAE-FI' ";
            }

            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
            
                $statement = $db_p->prepare($query1);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query2);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                if(!$App) {
                    $statement->closeCursor();
                    $statement = $db_p->prepare($query3);
                    $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                    $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                    $statement->execute();
                    $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
                }
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query4);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

                $statement->closeCursor();
                $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
                $total = toFloat($total);
                if($total > 0) {
                    echo "<tr>
                        <td class='w3-border'><i class='fa fa-arrow-right'></i> " . $id_cours . "</td>
                        <td class='w3-border'>" . $total . " H eqTD</td>
                    </tr>";
                }
            }
            echo "</tbody></table>";
        }
        $result = null;

    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}

//Tableau des heures de but3 - vacataires
function tabBUT3($db_p, $id_ens_p, $App = false) {
    $TP = 0;
    $TD = 0;
    $CM = 0;
    $SAE = 0;
    $total = 0;
    $query1 = null;
    $query2 = null;
    $query3 = null;
    $query4 = null;

    try {
        $fields = array(
            "sum(duree_seance) as duree_seance",
        );
        $query = "SELECT ".implode(", ", $fields)." ";
        $query .= "FROM seances ";
        $query .= "WHERE id_ens = :id_ens AND annee_scolaire = '" . SELECTED_YEAR . "' ";

        if($App) {
            $query .= "AND (id_cours like 'R5.%' ";
            $query .= "OR id_cours like 'R6.%' ";
            $query .= "OR id_cours like 'P5.%' ";
            $query .= "OR id_cours like 'P6.%') ";
            $query1 = $query . "AND type_seance like 'CM-APP' ";
            $query2 = $query . "AND (type_seance like 'TD2' ";
            $query2 .= "OR type_seance like 'TD3') ";
            $query4 = "SELECT ".implode(", ", $fields)." 
                FROM seances 
                WHERE id_ens = :id_ens
                AND annee_scolaire = '" . SELECTED_YEAR . "'
                AND (id_cours like 'SAE5%' or id_cours like 'SAE6%') 
                AND type_seance like 'SAE-APP' ";
        } else {
            $query .= "AND (id_cours like 'R5.%' ";
            $query .= "OR id_cours like 'R6.%' ";
            $query .= "OR id_cours like 'P5.%' ";
            $query .= "OR id_cours like 'P6.%') ";
            $query1 = $query . "AND type_seance like 'CM' ";
            $query2 = $query . "AND type_seance like 'TD1' ";
            $query3 = $query . "AND (type_seance like 'TPA' ";
            $query3 .= "OR type_seance like 'TPB') ";
            $query4 = "SELECT ".implode(", ", $fields)." 
                FROM seances 
                WHERE id_ens = :id_ens
                AND annee_scolaire = '" . SELECTED_YEAR . "'
                AND (id_cours like 'SAE5%' or id_cours like 'SAE6%') 
                AND type_seance like 'SAE-FI' ";
        }
        
        $statement = $db_p->prepare($query1);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $statement = $db_p->prepare($query2);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        if (!$App) {
            $statement->closeCursor();
            $statement = $db_p->prepare($query3);
            $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
            $statement->execute();
            $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        }

        $statement->closeCursor();
        $statement = $db_p->prepare($query4);
        $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
        $statement->execute();
        $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

        $statement->closeCursor();
        $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
        $total = toFloat($total);
        if($total > 0) {
            $title = $App ? "BUT3 APP - " . toFloat($total) : "BUT3 FI - " . toFloat($total) . " H eqTD";
            echo "<table class='w3-table w3-bordered w3-border w3-margin-bottom'><thead class='w3-light-gray'><tr><th colspan=2>" . $title . " H eqTD</th></tr></thead><tbody>";
            
            $TP = 0;
            $TD = 0;
            $CM = 0;
            $SAE = 0;
            $total = 0;
            
            $fields = array(
                "distinct(id_cours)",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens ";
            $query .= "AND (id_cours like 'R5.%' ";
            $query .= "OR id_cours like 'R6.%' ";
            $query .= "OR id_cours like 'P5.%' ";
            $query .= "OR id_cours like 'P6.%' ";
            $query .= "OR id_cours like 'SAE5%' ";
            $query .= "OR id_cours like 'SAE6%') AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            $query .= "ORDER BY id_cours ";

            $statement = $db_p->prepare($query);
            $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            $statement->closeCursor();
            $fields = array(
                "sum(duree_seance) as duree_seance",
            );
            $query = "SELECT ".implode(", ", $fields)." ";
            $query .= "FROM seances ";
            $query .= "WHERE id_ens = :id_ens ";
            $query .= "AND id_cours = :id_cours AND annee_scolaire = '" . SELECTED_YEAR . "' ";
            if ($App) {
                $query1 = $query . "AND type_seance like 'CM-APP' ";
                $query2 = $query . "AND (type_seance like 'TD2' OR type_seance like 'TD3') ";
                $query4 = $query . "AND type_seance like 'SAE-APP' ";
            } else {
                $query1 = $query . "AND type_seance like 'CM' ";
                $query2 = $query . "AND type_seance like 'TD1' ";
                $query3 = $query . "AND (type_seance like 'TPA' OR type_seance like 'TPB') ";
                $query4 = $query . "AND type_seance like 'SAE-FI' ";
            }

            foreach ($result as $row) {
                $id_cours = sanitize($row['id_cours']);
            
                $statement = $db_p->prepare($query1);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $CM = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                $statement->closeCursor();
                $statement = $db_p->prepare($query2);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $TD = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
        
                if(!$App) {
                    $statement->closeCursor();
                    $statement = $db_p->prepare($query3);
                    $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                    $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                    $statement->execute();
                    $TP = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];
                }

                $statement->closeCursor();
                $statement = $db_p->prepare($query4);
                $statement->bindValue(':id_ens', $id_ens_p, PDO::PARAM_STR);
                $statement->bindValue(':id_cours', $id_cours, PDO::PARAM_STR);
                $statement->execute();
                $SAE = $statement->fetch(PDO::FETCH_ASSOC)['duree_seance'];

                $statement->closeCursor();
                $total = $CM*1.5 + $TD + $SAE + $TP*2.0/3.0;
                $total = toFloat($total);
                if($total > 0) {
                    echo "<tr>
                        <td class='w3-border'><i class='fa fa-arrow-right'></i> " . $id_cours . "</td>
                        <td class='w3-border'>" . $total . " H eqTD</td>
                    </tr>";
                }
            }
            echo "</tbody></table>";
        }
        $result = null;

    } catch (Error|Exception $e) {
        echo 'Erreur' . $e->getMessage();
    }
}


$pageTitle = $prenom . ' ' . $nom;

