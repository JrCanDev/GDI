<?php
include "$root/inc/head.php";
?>

<div class="w3-responsive">
  <form method='GET' class="w3-left">
    <input type='hidden' name='page' value='maquette'>
    <input type='hidden' name='action' value='vue1'>
    <button type='submit' class="hidden-button">
      <div class="w3-border maquette-icon clickable">
        <i class="fa-regular fa-calendar-days" style="font-size: max(28px, 3.8vw);"></i>
      </div>
    </button>
  </form>

  <div class="margin w3-border w3-padding w3-responsive" style="background: white;">

    <div class="w3-bar w3-full w3-margin-top">
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-white w3-text-blue" onclick="chooseTab(event, '1')">S1</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '2')">S2</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '3')">S3</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '4')">S4</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '4APP')">S4 APP</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '5')">S5</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '5A')">S5 APP PA</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '5B')">S5 APP PB</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '6')">S6</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '6A')">S6 APP PA</button>
      <button class="w3-bar-item w3-button w3-border w3-border-blue w3-blue" onclick="chooseTab(event, '6B')">S6 APP PB</button>
    </div>

    <div id="1" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: block;">
      <?= generateTable($db, '1'); ?>
    </div>

    <div id="2" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '2'); ?>
    </div>

    <div id="3" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '3'); ?>
    </div>

    <div id="4" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '4'); ?>
    </div>

    <div id="4APP" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '4APP'); ?>
    </div>

    <div id="5" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '5'); ?>
    </div>

    <div id="5A" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '5A'); ?>
    </div>

    <div id="5B" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '5B'); ?>
    </div>

    <div id="6" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '6'); ?>
    </div>

    <div id="6A" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '6A'); ?>
    </div>

    <div id="6B" class="tabcontent w3-bordered w3-border w3-border-blue w3-padding w3-left-align" style="margin: none; display: none;">
      <?= generateTable($db, '6B'); ?>
    </div>

  </div>
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