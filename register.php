<?php
session_start();

$db = require(dirname(__FILE__) . '/lib/pdo.php');
$preventBackground = true;
?>
<!doctype html>
<html lang="fr">

<head class="fullHead">
    <meta charset="utf-8">
    <title>GDI - Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
</head>

<body>
    <div class="maincontent w3-center">
        <div class="dtitle w3-container main-background-color">
            <h1>Inscription</h1>
        </div>

        <div class="w3-center">
            <form action="check_register.php" method="post" id="registerForm" onsubmit=validateForm() style="display: inline-block;">
                <div class="w3-card w3-padding w3-margin w3-row w3-auto">
                    <div class="w3-row-padding">
                        <div class="w3-container w3-margin-top w3-margin-bottom w3-third">
                            <label for="uname" style="display: block"><b>Nom d'utilisateur</b></label>
                            <input type="text" id="uname" placeholder="tomtom" name="uname" required>
                        </div>
                        <div class="w3-container w3-margin-top w3-margin-bottom w3-third">
                            <label for="psw" style="display: block"><b>Mot de passe</b></label>
                            <input type="password" id="psw" placeholder="mdp" name="psw" required>
                        </div>
                        <div class="w3-container w3-margin-top w3-margin-bottom w3-third">
                            <label for="pswconfirm" style="display: block"><b>Confirmer mot de passe</b></label>
                            <input type="password" id="pswconfirm" placeholder="mdp" name="pswconfirm" required>
                        </div>
                    </div>
                    <div class="w3-row-padding">
                        <div class="w3-container w3-margin-top w3-margin-bottom w3-half">
                            <label for="lname" style="display: block"><b>Nom de l'enseignant</b></label>
                            <input type="text" id="lname" placeholder="Peterson" name="lname" required>
                        </div>
                        <div class="w3-container w3-margin-top w3-margin-bottom w3-half">
                            <label for="fname" style="display: block"><b>Prénom de l'enseignant</b></label>
                            <input type="text" id="fname" placeholder="Henry" name="fname" required>
                        </div>
                    </div>
                    <div class="w3-margin">
                        <?php if (is_null($db)) {
                        ?>
                            <input type="submit" name="register" value="Impossible de s'enregistrer" disabled class="w3-light-green w3-button"/>
                        <?php
                        } else {
                        ?>
                            <input type="submit" name="register" value="S'enregistrer" class="w3-light-green w3-button"/>
                        <?php
                        }
                        ?>
                        <button onclick="event.preventDefault(); redirectToLogin()" class="w3-blue-gray w3-button"><b>Retour à la connexion</b></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function validateForm() {
            let psw = document.forms["registerForm"]["psw"].value;
            let pswc = document.forms["registerForm"]["pswconfirm"].value;
            if (psw != pswc) {
                event.preventDefault();
                alert("Les mots de passes sont différents");
                return false;
            }
        }

        function redirectToLogin() {
            window.location.href = 'index.php';
        }
    </script>
<?= include "./inc/footer.php" ?>