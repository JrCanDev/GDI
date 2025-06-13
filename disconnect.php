<?php
session_start();
session_destroy();
header("Location: index.php");
$_SESSION['mesgs']['confirm'][] = 'Déconnecté avec succés';
exit();
?>