<?php
include "$root/inc/head.php";
?>
<div class="margin w3-border w3-padding" style="background: white;">
  <div class="w3-responsive" style="display:flex;">
    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="id_cours" class="w3-text-blue w3-left-align">
        <p style="margin: 0;"><i>id cours</i></p>
      </label>
      <input type="text" id="id_cours" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="intitule" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>intitule</i></p>
      </label>
      <input type="text" id="intitule" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="id_ens" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>id_ens</i></p>
      </label>
      <input type="text" id="id_ens" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="prenom" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>prénom</i></p>
      </label>
      <input type="text" id="prenom" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="nom" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>nom</i></p>
      </label>
      <input type="text" id="nom" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="type seance" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>type séance</i></p>
      </label>
      <input type="text" id="type seance" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="duree seance" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>durée séance</i></p>
      </label>
      <input type="text" id="duree seance" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="mutualisation" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>mutualisation</i></p>
      </label>
      <input type="text" id="mutualisation" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="Paiement" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>Paiement</i></p>
      </label>
      <input type="text" id="Paiement" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>

    <div class="w3-container" style="padding-left: 5px; padding-right: 5px;">
      <label for="commentaire" class="w3-text-blue w3-left-align">
      <p style="margin: 0;"><i>commentaire</i></p>
      </label>
      <input type="textfield" id="commentairekk" class="w3-input w3-border w3-margin-bottom table-input" placeholder="Rechercher un cours...">
    </div>
  </div>

  <div class="w3-responsive">
    <table class="w3-table w3-border w3-center w3-margin-bottom" id="filter-table">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th class="w3-border w3-center">id_cours</th>
          <th class="w3-border w3-center">intitule_cours</th>
          <th class="w3-border w3-center">id_ens</th>
          <th class="w3-border w3-center">prenom_ens</th>
          <th class="w3-border w3-center">nom_ens</th>
          <th class="w3-border w3-center">type_seance</th>
          <th class="w3-border w3-center">duree_seance</th>
          <th class="w3-border w3-center">mutualisation</th>
          <th class="w3-border w3-center">Paiement*</th>
          <th class="w3-border w3-center">Commentaire</th>
        <tr>
      </thead>
      <tbody>
        <?php getDonneesBrutes($db) ?>
      </tbody>
    </table>
  </div>
  <p class="w3-left-align" style="font-size: 13px;">
  * Signification des paiements :<br />
  &nbsp;&nbsp;BDC : paiement classiques sur les heures déclarées dans la base des charges<br />
  &nbsp;&nbsp;ORE : paiement sur des heures ORE accordées par l'IUT<br />
  &nbsp;&nbsp;NP : Non payé - affecté à des heures SAE que font les étudiants mais pas toutes encadrées. L'enseignant, sur ces séances là est Monsieur SAE. Les heures SAE payées sont celles où l'enseignant n'est pas SAE.
  </p>
  
</div>

<script>
function filterTable() {
  const inputs = document.querySelectorAll('input.table-input');
  const table = document.getElementById('filter-table');
  const rows = table.getElementsByTagName('tr');
  
  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td');
    let rowMatches = true;

    for (let j = 0; j < inputs.length; j++) {
      const inputValue = inputs[j].value.toLowerCase().trim();
      if(inputValue) {
        const cellText = cells[j] ? cells[j].textContent.toLowerCase() : '';
        if (!cellText.includes(inputValue)) {
          rowMatches = false;
          break;
        }
      }
    }

    rows[i].style.display = rowMatches ? '' : 'none';
  }
  updateInputWidth();
}

document.querySelectorAll('input.table-input').forEach(input => {
  input.addEventListener('input', filterTable);
});

function updateInputWidth() {
  var table = document.getElementById('filter-table');
  var inputs = document.querySelectorAll('input.table-input');

  inputs.forEach((input, index) => {
    if (table.rows[0].cells[index]) {
      input.style.width = table.rows[0].cells[index].offsetWidth - 10 + 'px';
    }
  });
};

window.addEventListener('load', updateInputWidth);
window.addEventListener('resize', updateInputWidth);
</script>

<?php
include "$root/inc/footer.php";
?>