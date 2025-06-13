<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding" style="background: white;">

    <div class="tabcontent w3-padding w3-left-align w3-margin-top" style="margin: none; display: block;">
        <h3 class="w3-margin-top"><b>Résumé</b></h3>
        <p><b>Nombre d'heures total :</b> <?php echo $resume; ?> H</p>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>

        <h3 class="w3-margin-bottom w3-margin-top"><b>Intervenants</b></h3>
        <div class="w3-responsive">
            <table class="w3-table w3-bordered w3-border">
                <thead class="w3-light-gray">
                    <tr>
                        <th class="w3-border">Groupe</th>
                        <th class="w3-border">Enseignant</th>
                        <th class="w3-border">Nombre d'heures</th>
                        <th class="w3-border">Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php getServices($id_cours); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
include "$root/inc/footer.php"; 
?>