<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding w3-left-align" style="background: white;">
    <h3 class="w3-margin-bottom w3-margin-top"><b>Récupération de mot de passe</b></h3>

    <div class="w3-center">
        <form method="POST" action="index.php?page=motDePasseOublie" style="display: inline-block; width: 100%;">
            <div class="w3-card w3-padding w3-row w3-auto" style="background: white;">
                <p>Entrez votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
                <div class="w3-container w3-margin-top w3-margin-bottom">
                    <label for="email" style="display: block"><b>Adresse e-mail</b></label>
                    <input type="email" id="email" name="email" placeholder="tomtom@univ-littoral.fr" required style="width: 100%; padding: 8px;">
                </div>
                <div class="w3-margin">
                    <input type="submit" value="Envoyer le lien" class="w3-light-green w3-button"/>
                    <a href="index.php" class="w3-button w3-gray" style="text-decoration: none;"><b>Retour</b></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include "$root/inc/footer.php";
?>
