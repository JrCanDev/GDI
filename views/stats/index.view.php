<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding w3-left-align" style="background: white;">
    <h3 class="w3-margin-bottom w3-margin-top"><b>Données brutes dans la base de données</b></h3>
    
    <div style="display:flex; flex-direction: row; justify-content: left; align-items: left;">
        <h4 style="margin: 0;">Décrouvrir</h4>
        <form method='GET'>
            <input type='hidden' name='page' value='stats'>
            <input type='hidden' name='action' value='brutes'>
            <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                <h4 style="margin: 0;"><b><i>ici</i></b></h4>
            </button>
        </form>
    </div>

    <div class="w3-border w3-margin-top w3-margin-bottom"></div>

    <h3 class="w3-margin-bottom w3-margin-top"><b>Nombre d'heures étudiants</b></h3>
    <form method='GET'>
        <input type='hidden' name='page' value='stats'>
        <input type='hidden' name='action' value='semestres'>
        <button type='submit' class='w3-text-blue w3-left-align' 
        style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <h4 style="margin: 0;"><b><i>Par semestre et groupes</i></b></h4>
        </button>
    </form>

    <div class="w3-border w3-margin-top w3-margin-bottom"></div>

    <h3 class="w3-margin-bottom w3-margin-top"><b>Nombre d'heures étudiants - PN vs Calais</b></h3>
    <form method='GET'>
        <input type='hidden' name='page' value='stats'>
        <input type='hidden' name='action' value='PNvsC'>
        <input type='hidden' name='BUT' value='1'>
        <button type='submit' class='w3-text-blue w3-left-align' 
            style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
                <h4 style="margin: 0;"><b><i>Pour le BUT1</i></b></h4>
        </button>
    </form>
    <form method='GET'>
        <input type='hidden' name='page' value='stats'>
        <input type='hidden' name='action' value='PNvsC'>
        <input type='hidden' name='BUT' value='2A'>
        <button type='submit' class='w3-text-blue w3-left-align' 
        style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <h4 style="margin: 0;"><b><i>Pour le BUT2 parcours A</i></b></h4>
        </button>
    </form>
    <form method='GET'>
        <input type='hidden' name='page' value='stats'>
        <input type='hidden' name='action' value='PNvsC'>
        <input type='hidden' name='BUT' value='2B'>
        <button type='submit' class='w3-text-blue w3-left-align' 
        style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <h4 style="margin: 0;"><b><i>Pour le BUT2 parcours B</i></b></h4>
        </button>
    </form>
    <form method='GET'>
        <input type='hidden' name='page' value='stats'>
        <input type='hidden' name='action' value='PNvsC'>
        <input type='hidden' name='BUT' value='3A'>
        <button type='submit' class='w3-text-blue w3-left-align' 
        style='cursor: pointer; border: none; background-color: white; font-size: 13px;'>
            <h4 style="margin: 0;"><b><i>Pour le BUT3 parcours A</i></b></h4>
        </button>
    </form>
</div>

<?php
include "$root/inc/footer.php";
?>