<?php
  try {
    if (!isset($_SESSION)) {
      session_start();
    }

    if (!isset($db)) {
      $db = require "$root/lib/pdo.php";
    }

    $stmt = $db->query("SELECT id_as FROM annee_scolaire ORDER BY id_as DESC");
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_SESSION['annee'])) {
      $selected = $_SESSION['annee'];
      $first_load = false;
    } else {
      $first_load = true;
      $selected = $years[0]['id_as'];
      $_SESSION['annee'] = $selected;
    };

  } catch (Throwable $e) {
    echo ''. $e->getMessage() .'';
  }
?>

<!-- Navbar -->
<div class="w3-display-container top_header" style="min-height: 100px; max-height: 200px;">
  <div class="w3-padding w3-display-left logo-container">
    <img src="/img/logo.png" alt="Logo" class="logo" style="height: 75px">
  </div>
  <div class="w3-padding w3-display-middle w3-center">
    <p class="title"><b><?= isset($pageTitle) ? sanitize($pageTitle) : 'Gestion du Département informatique' ?></b></p>
  </div>
  <div class="w3-padding w3-display-topright w3-margin" style="display:flex; flex-direction: row; justify-content: center; align-items: center">
    <h4 class="title" style="padding-right: 20px"><?= $_SESSION['user']['nom_ens'] == 'admin_nom' ? 'admin' : sanitize($_SESSION['user']['nom_ens']) . " " . sanitize($_SESSION['user']['prenom_ens'])?></h4>
    <img src="/img/exit.png" class="clickable" alt="exit" id="disconnectImg" style="height: 50px">
  </div>
</div>

<div>
  <nav class="w3-bar w3-card">
    <form method='GET'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Accueil</b>
      </button>
    </form>
    <form method='GET'>
      <input type='hidden' name='page' value='services'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Services</b>
      </button>
    </form>
    <form method='GET'>
      <input type='hidden' name='page' value='maquette'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Maquette</b>
      </button>
    </form>
    <!-- <form method='GET'>
      <input type='hidden' name='page' value='stats'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Stats/données brutes</b>
      </button>
    </form> -->
    <?php if (authClass::checkPriviledgeVacataire($_SESSION['user']['nom_util'])) { ?>
    <form method='GET'>
      <input type='hidden' name='page' value='vaca'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Coordonnées des vacataires</b>
      </button>
    </form>
    <?php } if (authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util'])) { ?>
    <form method='GET'>
      <input type='hidden' name='page' value='bd'>
      <button type='submit' class="w3-bar-item w3-button headButton">
        <b>Base de données</b>
      </button>
    </form>
    <?php } ?>
    <select id="choose_year" class="w3-right w3-padding">
      <?php
      echo authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util']) ? "<option value='new_year'>Créer année</option>" : "";
      ?>
      <?php foreach ($years as $year) { ?>
        <option value="<?= $year['id_as'] ?>" <?php if ($selected == $year['id_as']) echo 'selected' ?>><?= sanitize($year['id_as']) ?></option>
      <?php } ?>
    </select>
  </nav>
</div>

<script>
  <?php
  echo authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util']) && (isset($first_load) && $first_load === true) ? "document.getElementById('mySelect').selectedIndex = 0;" : "";
  ?>

  document.getElementById('disconnectImg').addEventListener('click', function() {
      window.location.href = '/disconnect.php';
  });

  document.querySelectorAll('.headButton').forEach(button => {
    button.addEventListener('click', function () {
      const pageUrl = this.getAttribute('data-url')
      window.location.href = pageUrl;
    });
  });

  document.getElementById('choose_year').addEventListener('change', function() {
    if (this.value === 'new_year') {
      window.location.href = 'index.php?page=new_year';
      return;
    }
    $.ajax({
      url: '/inc/top.year.ajax.php',
      type: 'POST',
      data: {
        selected: this.value
      },
      success: function(response) {
        location.reload();
      }
    });
  });

  function adjustTextSize() {
    const textElements = document.querySelectorAll('.title');
    const screenWidth = window.innerWidth;

    let fontSize;
    if (screenWidth < 700) {
      fontSize = '0px';
    } else if (screenWidth < 800) {
      fontSize = '12px';
    } else if (screenWidth < 1100) {
      fontSize = '16px';
    } else {
      fontSize = '24px';
    }

    textElements.forEach(element => {
      element.style.fontSize = fontSize;
    });
  }

  window.addEventListener('load', adjustTextSize);
  window.addEventListener('resize', adjustTextSize);
</script>