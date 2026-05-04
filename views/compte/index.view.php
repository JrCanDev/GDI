<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding w3-left-align" style="background: white;">
    <h3 class="w3-margin-bottom w3-margin-top"><b>Résumé du compte</b></h3>
    
    <div class="w3-container">
        <p><strong>Nom d'utilisateur :</strong> <?php echo sanitize($userData['nom_util']); ?></p>
        <p><strong>Nom :</strong> <?php echo sanitize($userData['nom_ens']); ?></p>
        <p><strong>Prénom :</strong> <?php echo sanitize($userData['prenom_ens']); ?></p>
        <p><strong>Email :</strong> <?php echo sanitize($userData['mail_ens']); ?></p>
        <p><strong>Téléphone :</strong> <?php echo sanitize($userData['tel_ens']); ?></p>
        <p><strong>Ville :</strong> <?php echo sanitize($userData['ville_ens']); ?></p>
        <p><strong>Statut :</strong> <?php echo $userData['titulaire_ens'] == 't' ? 'Titulaire' : 'Non titulaire'; ?></p>
        <p><strong>Rôles :</strong> 
            <?php // un utilisateur peu avoir plusieur rôle en même temps : 
            $roles = [];
            if ($userData['admin'] == 't') $roles[] = 'Administrateur';
            if ($userData['vacataire'] == 't') $roles[] = 'Vacataire';
            if ($userData['budget'] == 't') $roles[] = 'Gestionnaire Budget';
            if ($roles == []) $roles[] = 'Utilisateur standard'; 
            echo implode(', ', $roles);
            ?>
        </p>
    </div>
    
    <!-- bouton changer le mot de passe ici --> 
</div>

<?php
include "$root/inc/footer.php";
?>
