<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding w3-left-align" style="background: white;">
    <h3 class="w3-margin-bottom w3-margin-top"><b>Changement de mot de passe</b></h3>

    <div class="w3-center">
        <form method="POST" action="index.php?page=MAJMotDePasse" style="display: inline-block; width: 100%;">
            <div class="w3-card w3-padding w3-row w3-auto" style="background: white;">
                <div class="w3-row">
                    <div class="w3-container w3-margin-top w3-margin-bottom w3-half">
                        <label for="new_password" style="display: block"><b>Nouveau mot de passe</b></label>
                        <input type="password" id="new_password" placeholder="Nouveau mot de passe" name="new_password" required style="width: 100%;">
                    </div>
                    <div class="w3-container w3-margin-top w3-margin-bottom w3-half">
                        <label for="confirm_password" style="display: block"><b>Confirmer mot de passe</b></label>
                        <input type="password" id="confirm_password" placeholder="Confirmer mot de passe" name="confirm_password" required style="width: 100%;">
                    </div>
                </div>
                
                <div class="w3-margin">
                    <input type="submit" value="Valider le changement" class="w3-light-green w3-button"/>
                    <a href="index.php?page=compte" class="w3-blue-gray w3-button" style="text-decoration: none;"><b>Annuler</b></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include "$root/inc/footer.php";
?>
