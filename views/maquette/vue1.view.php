<?php
include "$root/inc/head.php";
?>

<div class="w3-responsive">
  <form method='GET' class="w3-left">
    <input type='hidden' name='page' value='maquette'>
    <input type='hidden' name='action' value='vue2'>
    <button type='submit' class="hidden-button">
      <div class="w3-border maquette-icon clickable">
        <i class="fa-regular fa-clock w3-left" style="font-size: max(28px, 3.8vw);"></i>
      </div>
    </button>
  </form>

  <div class="margin w3-border w3-padding" style="background: white;">
    <form id="filter-form" style="display: inline-flex;">
      <div class="w3-container">
        <label for="id_cours" class="w3-text-blue w3-left-align">
          <p style="margin: 0;"><i>Id cours</i></p>
        </label>
        <input type="text" id="id_cours" class="w3-input w3-border w3-margin-bottom table-input" placeholder="id de cours">
      </div>

      <div class="w3-container">
        <label for="nom_ens" class="w3-text-blue w3-left-align">
          <p style="margin: 0;"><i>Nom enseignant</i></p>
        </label>
        <input type="text" id="nom_ens" class="w3-input w3-border w3-margin-bottom" placeholder="Tom">
      </div>

      <div class="w3-container">
        <label for="prenom_ens" class="w3-text-blue w3-left-align">
          <p style="margin: 0;"><i>Prénom enseignant</i></p>
        </label>
        <input type="text" id="prenom_ens" class="w3-input w3-border w3-margin-bottom" placeholder="Cruz">
      </div>

      <div class="w3-container">
        <label for="type_seance" class="w3-text-blue w3-left-align">
          <p style="margin: 0;"><i>Type de séance</i></p>
        </label>
        <input type="text" id="type_seance" class="w3-input w3-border w3-margin-bottom" placeholder="CM">
      </div>

      <div class="w3-container">
        <input type="submit" id="submit_button" class="w3-button w3-border w3-margin w3-light-grey" value="Appliquer">
      </div>
    </form>
    <div id="tables">
      <h1>Les tables sont en cours de génération</h1>
    </div>
  </div>
</div>

<script>
  let filters = {};

  $(document).ready(function() {
    // Initialize the tables
    refreshTables();

    // Event listener for the filter form submission
    $('#filter-form').on('submit', function(event) {
      event.preventDefault();
      const formData = {
        'id_cours': $('#id_cours').val(),
        'nom_ens': $('#nom_ens').val(),
        'prenom_ens': $('#prenom_ens').val(),
        'type_seance': $('#type_seance').val()
      };

      filters = formData;
      refreshTables(formData);
    });
  });

  function refreshTables(filters = {}) {
    $.ajax({
      url: 'controllers/maquette/vue1/refreshTables.php',
      type: 'GET',
      data: {
        filters: filters,
      },
      success: function(response) {
        $('#tables').html(response);
        adjustSpacer();
      },
      error: function(response) {
      },
    });
  }

  function toggleModal($id) {
    const modal = document.getElementById($id);
    if (modal.style.display === 'block') {
      modal.style.display = 'none';
    } else {
      modal.style.display = 'block';
    }
  }

</script>

<?php
include "$root/inc/footer.php";
?>