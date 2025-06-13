<?php
include "$root/inc/head.php";
?>
<div>
  <div class="paraline w3-opacity">
    <h1><b>Services Par Enseignants</b></h1>
  </div>

  <div class="margin w3-border w3-padding" style="background: white;">
    <div class="dynamic-grid enseignants" id="gridEns" data-items='<?php getServicesByEnseignants() ?>'></div>
  </div>

  <div class="paraline w3-opacity">
    <h1><b>Services Par Enseignements</b></h1>
  </div>

  <div class="margin w3-border w3-padding" style="background: white;">
    <h2><b>S1</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS1" data-items='<?php getServicesByEnseignements(1) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS1P" data-items='<?php getServicesByEnseignements(1, true) ?>'></div>
    </div>

    <h2><b>S2</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS2" data-items='<?php getServicesByEnseignements(2) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS2P" data-items='<?php getServicesByEnseignements(2, true) ?>'></div>
    </div>

    <h2><b>S3</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS3" data-items='<?php getServicesByEnseignements(3) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS3P" data-items='<?php getServicesByEnseignements(3, true) ?>'></div>
    </div>

    <h2><b>S4</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS4" data-items='<?php getServicesByEnseignements(4) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS4P" data-items='<?php getServicesByEnseignements(4, true) ?>'></div>
    </div>

    <h2><b>S5</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS5" data-items='<?php getServicesByEnseignements(5) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS5P" data-items='<?php getServicesByEnseignements(5, true) ?>'></div>
    </div>

    <h2><b>S6</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS6" data-items='<?php getServicesByEnseignements(6) ?>'></div>
    </div>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS6P" data-items='<?php getServicesByEnseignements(6, true) ?>'></div>
    </div>
  </div>


  <div class="paraline w3-opacity">
    <h1><b>Service par autres enseignements / responsabilités, etc.</b></h1>
  </div>

  <div class="margin w3-border w3-padding w3-responsive" style="background: white;">
    <h2><b>Suivi de stage et apprenti</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS6" data-items='<?php getServicesByEnseignements(0,false, 'STG-APP') ?>'></div>
    </div>

    <h2><b>Évènements</b></h2>
    <div class="w3-border w3-border-grey w3-margin-bottom">
      <div class="dynamic-grid enseignements" id="gridS6" data-items='<?php getServicesByEnseignements(0,false, 'EVT') ?>'></div>
    </div>

    <h2><b>Responsabilités</b></h2>
    <div class="w3-responsive">
      <table class="w3-table w3-border w3-center w3-margin-bottom">
        <thead>
          <tr style="background-color: #f2f2f2;">
            <th class="w3-border w3-center">Groupe</th>
            <th class="w3-border w3-center">Responsabilité</th>
            <th class="w3-border w3-center">Enseignant</th>
            <th class="w3-border w3-center">Nombre d'heures</th>
          </tr>
        </thead>
        <tbody>
        <?php getServicesByResp() ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function createGrid(container) {
  const screenWidth = window.innerWidth;
  const containerWidth = container.clientWidth;
  const listData = JSON.parse(container.getAttribute('data-items') || '[]');
  let columns;

  if (screenWidth < 400) {
    columns = 1;
  } else if (screenWidth < 600) {
    columns = 2;
  } else if (screenWidth < 900) {
    columns = 4;
  } else if (screenWidth < 1200) {
    columns = 5;
  } else {
    columns = 6;
  }

  if (listData.length < columns) {
    columns = listData.length;
  }

  container.innerHTML = '';
  container.style.display = 'grid';
  container.style.gridTemplateColumns = `repeat(${columns}, minmax(100px, auto))`; // <-- allow auto width

  listData.forEach(itemData => {
    const item = document.createElement('div');
    item.className = 'w3-left-align';
    item.style.border = 'black solid 1px';
    item.style.fontSize = '12px';
    item.style.minHeight = '40px';
    item.innerHTML = `
      <form method='GET'>
        <input type='hidden' name='page' value='services'>
        <input type="hidden" name="id" value="${itemData[0]}">
        <input type="hidden" name="action" value="${container.classList.contains('enseignants') ? 'enseignant' : container.classList.contains('enseignements') ? 'enseignement' : ''}">
        <button type="submit" class="w3-text-blue w3-left-align" 
          style="cursor: pointer; border: none; background-color: white; font-size: 13px;">
          <b>${itemData[1]}</b>
        </button>
      </form>`;
    container.appendChild(item);
  });
}

function updateGrids() {
  document.querySelectorAll('.dynamic-grid').forEach(container => {
    createGrid(container);
  });
}

window.addEventListener('load', updateGrids);
window.addEventListener('resize', updateGrids);
</script>

<?php
include "$root/inc/footer.php";
?>
