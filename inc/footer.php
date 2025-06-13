</div>
<div style="left:0;"></div>
<?php
session_start();
$errors = isset($_SESSION['mesgs']['errors']) ? $_SESSION['mesgs']['errors'] : [];
$confirms = isset($_SESSION['mesgs']['confirm']) ? $_SESSION['mesgs']['confirm'] : [];
$errors = json_encode($errors);
$confirms = json_encode($confirms);
unset($_SESSION['mesgs']['errors']);
unset($_SESSION['mesgs']['confirm']);

if ($db) {
    $db = NULL;
}
?>

<div class="spacer" <?= $preventBackground ? '' : 'style="background: lightgrey ;"'?>></div>

<script>
  var errors = <?= $errors ?>;
  var confirms = <?= $confirms ?>;

  setTimeout(function() {
    if (errors != "") {
      errors.forEach(error => {
        alert(error);
      });
    }
  }, 300);

  setTimeout(function() {
    if (confirms != "") {
      confirms.forEach(confirm => {
        alert(confirm);
      });
    }
  }, 300);

  window.addEventListener('load', adjustSpacer);
  window.addEventListener('resize', adjustSpacer);

  function adjustSpacer() {
    const body = document.body;
    const html = document.documentElement;

    const header = document.querySelector('.fullHead');
    const footer = document.querySelector('footer');
    const content = document.querySelector('.maincontent');
    const spacer = document.querySelector('.spacer');

    const totalUsedHeight =
      header.offsetHeight +
      footer.offsetHeight +
      content.offsetHeight;

    const windowHeight = window.innerHeight;

    const extraSpace = windowHeight - totalUsedHeight;
    spacer.style.height = extraSpace > 0 ? `${extraSpace+1}px` : '0';
  }
</script>

<footer class="w3-container w3-padding-16 w3-center w3-text-white main-background-color w3-xlarge">
  <a href="https://github.com/JrCanDev/GDI" target="_blank"><i class="fab fa-github w3-hover-opacity" aria-hidden="true"></i></a>
  <p class="w3-medium">Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
  <p class="w3-medium">Hébergé par <a href="https://github.com/JrCanDev" target="_blank">JrCanDev</a></p>
</footer>

</body>
</html>