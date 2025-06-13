<?php
include "$root/inc/head.php";
?>

<div class="w3-container margin w3-border w3-padding" style="background: white;">
	<div class="w3-margin w3-padding">
		<label for="select_year">L'année scolaire à copiée</label>
		<select id="select_year" class="w3-input w3-border w3-center">
		<?php foreach ($years as $year) { ?>
			<option value="<?= $year['id_as'] ?>" <?php if (SELECTED_YEAR == $year['id_as']) echo 'selected' ?>><?= sanitize($year['id_as']) ?></option>
		<?php } ?>
		</select>
	</div>
	<div class="w3-margin w3-padding">
		<label for="new_year">Nom de la nouvelle année scolaire créée</label>
		<input id="new_year" type="text" class="w3-input w3-border w3-center" placeholder="2024-2025"></input>
	</div>
	<button class="w3-button w3-blue" onclick="create_year()">Créer l'année</button>
</div>

<script>
  list_year = <?= json_encode($list_year) ?>;

	function create_year() {
    const year = document.getElementById('new_year').value.trim();
    const selected_year = document.getElementById('select_year').value.trim();
		const year1 = year.substr(0, 4);
		const year2 = year.substr(5);
		const separator = year[4];
		if (isYear(year1) && isYear(year2) & Number(year1)+1 === Number(year2) && year.length === 9) {
      if (separator === '-') {
        if (!list_year.includes(year)) {
          $.ajax({
            url: 'controllers/ajout_annee/copy/copy.php',
            type: 'POST',
            data: {
              new_year: year,
              old_year: selected_year
            },
            success: function(response) {
              console.log(response)
              response = JSON.parse(response)
              if (response.error != undefined) {
                alert(response.error)
              } else {
                alert(response.success)
                window.location.href = 'index.php'
              }
            },
            error: function(response) {
              alert('Impossible de contacter le serveur');
            },
          });
        } else {
          alert("L'année existe déjà");
        }
      } else {
        alert("Mauvais séparateur ('-')");
      }
		} else {
      alert("Le format d'année n'est pas correct (ex: 2024-2025)");
    }
	}

  // Check if the string is numeric and within a reasonable year range
  function isYear(str) {
    const year = Number(str);
    return Number.isInteger(year) && year >= 1000 && year <= 9999;
  }
</script>

<?php
include "$root/inc/footer.php";
?>