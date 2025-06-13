<?php
include "$root/inc/head.php";
?>

<div class="margin w3-border w3-padding" style="background: white;">
  <h3><b>Résumé</b></h3>
  <?php echo $total . " H" ?> 

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3><b>Enseignements du <?= $designation ?> semestre <?= $semester ?></b></h3>
  <div class="w3-responsive">
    <table class="w3-table w3-border w3-center w3-margin-bottom">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th class="w3-border w3-center">Cours</th>
          <th class="w3-border w3-center">Types de séances</th>
          <th class="w3-border w3-center">Enseignants</th>
          <th class="w3-border w3-center">Durée</th>
        </tr>
      </thead>
      <tbody>
        <?php getTab($db, $sqlCours) ?>
      </tbody>
    </table>
  </div>

<?php
include "$root/inc/footer.php";
?>