<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding" style="background: white;">

    <div class="w3-bar w3-full w3-margin-top">
        <button class="w3-bar-item w3-button w3-border w3-border-blue w3-white w3-text-blue" onclick="chooseTab(event, 'Résumé')">Résumé</button>
        <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, 'Détails')">Détails</button>
        <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, 'Dispo')">Informer des disponibilités</button>
        <?php if (!$titulaire) { ?>
        <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, 'Vaca')">Dossier vacataire</button>
        <?php } ?>
    </div>

    <div id="Résumé" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: block;">
        <h3 class="w3-margin-bottom w3-margin-top"><b>Ressources et portfolio</b></h3>
        <div>
            <table class="w3-table w3-striped w3-bordered w3-border">
                <thead class="w3-light-gray">
                    <tr>
                        <th class="w3-border">CM</th>
                        <th class="w3-border">TD</th>
                        <th class="w3-border">TP</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w3-border"><?php echo $CM_enseignant; ?> H</td>
                        <td class="w3-border"><?php echo $TD_enseignant; ?> H</td>
                        <td class="w3-border"><?php echo $TP_enseignant; ?> H</td>
                    </tr>
                </tbody>
            </table>
            <div class="w3-right-align">
            <?php if ($titulaire == 1) { ?>
                <p style="margin-bottom: 0;">Soit entre <b><?php echo $CM_enseignant*1.5+$TD_enseignant+2*$TP_enseignant/3 ?>H</b> (si 1h de TP = 0.66h de TD)</p>
                <p style="margin: 0;">et <b><?php echo $CM_enseignant*1.5+$TD_enseignant+$TP_enseignant ?>H</b> (si 1h de TP = 1h de TD)</p>
            <?php } else {?>
                <p style="margin: 0;">Soit <b><?php echo $CM_enseignant*1.5+$TD_enseignant+2*$TP_enseignant/3 ?>H</b> eqTD</p>
            <?php }?>
            </div>
        </div>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>

        <h3 class="w3-margin-bottom w3-margin-top"><b>Autres</b></h3>
        <div>
            <table class="w3-table w3-bordered w3-border">
                <thead class="w3-light-gray">
                    <tr>
                        <th class="w3-border">SAE</th>
                        <th class="w3-border">EVT</th>
                        <th class="w3-border">RNT</th>
                        <th class="w3-border">Hors-quota</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w3-border"><?php echo $SAE_enseignant; ?> H</td>
                        <td class="w3-border"><?php echo $EVT_enseignant; ?> H</td>
                        <td class="w3-border"><?php echo $RNT_enseignant; ?> H</td>
                        <td class="w3-border"><?php echo $Hors_quota_enseignant; ?> H</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="w3-border w3-margin-bottom w3-margin-top"></div>

        <h3 class="w3-margin-bottom w3-margin-top"><b>Total</b></h3>
        <?php if ($titulaire == 1) { ?>
            <p style="margin-bottom: 0;">Entre <b><?php echo ($CM_enseignant*1.5+$TD_enseignant+2*$TP_enseignant/3)+$SAE_enseignant+$EVT_enseignant+$RNT_enseignant ?>H</b> (si 1h de TP = 0.66h de TD)</p>
            <p style="margin: 0;">et <b><?php echo ($CM_enseignant*1.5+$TD_enseignant+$TP_enseignant)+$SAE_enseignant+$EVT_enseignant+$RNT_enseignant ?>H</b> (si 1h de TP = 1h de TD)</p>
        <?php } else {?>
            <p style="margin: 0;">Soit <b><?php echo ($CM_enseignant*1.5+$TD_enseignant+2*$TP_enseignant/3)+$SAE_enseignant+$EVT_enseignant+$RNT_enseignant ?>H</b> eqTD</p>
        <?php }?>
            <p style="margin: 0;"><b>+<?php echo $Hors_quota_enseignant ?>H de hors-quota</b></p>
    </div>

    <div id="Détails" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
        <h3><b>RNT</b> <i class="fa fa-arrow-right"></i> <mark style="background-color:red;">n'apparait pas dans l'EDT</mark></h3>
        <?php tabRNT($db, $id_ens); ?>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3><b>Hors-quota</b> <i class="fa fa-arrow-right"></i> <mark style="background-color:red;">n'apparait pas dans l'EDT</mark></h3>
        <?php tabHQ($db, $id_ens); ?>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3><b>Ressources et portfolio</b> <i class="fa fa-arrow-right"></i> <mark style="background-color:lime;">apparait dans l'EDT</mark></h3>
        <?php for ($i = 1; $i<7; $i++) tabRP($db, $id_ens, $i); ?>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3><b>SAE</b> <i class="fa fa-arrow-right"></i> <mark style="background-color:lime;">apparait dans l'EDT</mark></h3>
        <?php tabSAE($db, $id_ens); ?>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3><b>EVT</b> <i class="fa fa-arrow-right"></i> <mark style="background-color:red;">n'apparait pas dans l'EDT</mark></h3>
        <?php tabEVT($db, $id_ens); ?>
    </div>

    <div id="Dispo" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
        <h3 class="w3-margin-bottom w3-margin-top"><b>Instructions pour indiquer ses disponibilités</b></h3>
        <?php if ($titulaire) { ?>
        <h4>Accédez à <a class="w3-text-blue w3-hover-light-blue" target="_blank" href="https://univlittoralfr-my.sharepoint.com/:x:/g/personal/capitain_univ-littoral_fr/EVTlmxi6yx9NlcR5LPOO3gsB9-4bV26UxP5s-pzND3gq6A?rtime=0m2GoabH3Eg">ce classeur excel</a>. Cliquez sur la bonne feuille (onglet Titulaires en base de la fenêtre), ajoutez votre nom et prénom puis donnez vos disponiblités.</h4>
        <?php } else { ?>
        <h4>Accédez à <a class="w3-text-blue w3-hover-light-blue" target="_blank" href="https://univlittoralfr-my.sharepoint.com/:x:/g/personal/capitain_univ-littoral_fr/EVTlmxi6yx9NlcR5LPOO3gsB9-4bV26UxP5s-pzND3gq6A?rtime=0m2GoabH3Eg">ce classeur excel</a>. Cliquez sur la bonne feuille (onglet Titulaires en base de la fenêtre), ajoutez votre nom et prénom puis donnez vos disponiblités.</h4>
        <?php } ?>
    </div>

    <?php if (!$titulaire) { ?>
    <div id="Vaca" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
        <h3 class="w3-margin-bottom w3-margin-top"><b>À déclarer lors de la création de votre dossier vacataire (voir section suivante)</b></h3>
        <?php tabBUT1($db, $id_ens); ?>
        <?php tabBUT2($db, $id_ens); ?>
        <?php tabBUT2($db, $id_ens, true); ?>
        <?php tabBUT3($db, $id_ens); ?>
        <?php tabBUT3($db, $id_ens, true); ?>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3 class="w3-margin-bottom w3-margin-top"><b>Créer votre dossier vacataire</b></h3>
        <ol>
            <li>Rendez-vous sur <i><a class="link" target="_blank" href="https://e-vacataires.univ-littoral.fr/">la plateforme en ligne de recrutement des vacataires</a></i></li>
            <li>Prenez connaissance du guide d'utilisation puis cliquez sur "Créer un dossier de recrutement"</li>
            <li>Si vous n'avez pas encore de compte, créez-en un</li>
            <li>Suivez les instructions, puis en temps voulu, servez-vous des informations précédentes pour compléter votre demande.<br /><b>Afin d''éviter tout problème de déclaration/paiement, veuillez demander une autorisation à votre employeur qui couvre plus que le nombre d''heures totales prévues.</b> Par exemple, demandez une autorisation de 150h si le volume total prévu est de 120h.</li>
            <li>Connectez-vous régulièrement sur la plateforme pour vérifier si votre dossier est accepté ou s'il manque des pièces justificatives.</li>
        </ol>

        <div class="w3-border w3-margin-top w3-margin-bottom"></div>
        <h3 class="w3-margin-bottom w3-margin-top"><b>Créer votre compte ULCO</b></h3>
        <ol>
            <li>Prenez connaissance de <i><a class="link" target="_blank" href="pdf/charte_de_bon_usage_des_ressources_informatiques_et_des_reseaux.pdf">"La charte de bon usage des ressources informatiques et des réseaux de l'ULCO"</a></i></li>
            <li>Puis téléchargez et remplissez le formulaire de <i><a class="link" target="_blank" href="pdf/demande_ouverture_compte_numerique_ULCO_prerempli.pdf">"Demande d'ouverture d'un compte numérique ULCO"</a></i> qui est déjà prérempli.<br />Il vous faut compléter :<br />&rarr; La section <strong>"Le demandeur"</strong> avec vos nom, prénom, etc.<br />&rarr; La section <strong>"Réception de mes informations de connexion"</strong> avec votre adresse mail personnelle (où sera envoyé vos codes de connexion et sera utilisé comme adresse en cas de mot de passe oublié)<br />&rarr; La section <strong>"Engagement personnel"</strong> avec vos nom, prénom, lieu et date de signature. Sans oublier de signer bien entendu.</li>
            <li>Numérisez et envoyez par mail ce formulaire rempli à <i><a class="link" href="mailto:remi.synave@univ-littoral.fr?subject=demande ouverture compte ULCO'<?php echo preg_replace('/\s+/', '', $prenom . $nom) ?>'">Rémi Synave</a></i></li>
            <li>La suite de la procédure est gérée par votre serviteur &#x1F642;</li>
        </ol>
    </div>
    <?php } ?>
</div>

<script>
    function chooseTab(evt, category) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("w3-bar-item");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-white w3-text-blue", " w3-blue");
        }
        event.currentTarget.className = event.currentTarget.className.replace(" w3-blue", " w3-white w3-text-blue");
        document.getElementById(category).style.display = "block";

        adjustSpacer();
    }
</script>

<?php 
include "$root/inc/footer.php"; 
?>