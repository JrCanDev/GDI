<?php
include "$root/inc/head.php";
?>


<div class="margin w3-border w3-padding" style="background: white;">
  <h3 class="w3-margin-bottom w3-margin-top"><b>Ressources (basé sur le <?php switch ($BUT) {
    case '1':
      echo 'TPA du BUT1';
      break;
    case '2A':
      echo 'TPA du BUT2';
      break;
    case '2B':
      echo 'TPD du BUT2';
      break;
    case '3A':
      echo 'TPA du BUT3';
      break;
  } ?>)</b></h3>

  <div class="w3-responsive">
    <table class="w3-table w3-border w3-center w3-margin-bottom">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th class="w3-border w3-center">Cours</th>
          <th class="w3-border w3-center">Préconisation PN</th>
          <th class="w3-border w3-center">Calais</th>
          <th class="w3-border w3-center">&Delta;</th>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php getCompTab($db, $BUT) ?>
      </tbody>
    </table>
  </div>

  <div class="w3-border w3-margin-top w3-margin-bottom"></div>
  <h3 class="w3-margin-bottom w3-margin-top"><b>Portfolio et SAÉ</b></h3>
  <div class="w3-responsive">
    <table class="w3-table w3-border w3-center w3-margin-bottom">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th class="w3-border w3-center">Cours</th>
          <th class="w3-border w3-center">Préconisation PN</th>
          <th class="w3-border w3-center">Calais</th>
          <th class="w3-border w3-center">&Delta;</th>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php getCompTab($db, $BUT, 'portfolio') ?>
        <?php getCompTab($db, $BUT, 'sae1') ?>
        <?php getCompTab($db, $BUT, 'sae2') ?>
      </tbody>
    </table>
  </div>
</div>

<?php
include "$root/inc/footer.php";
?>