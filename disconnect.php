<?php
require_once dirname(__FILE__) . '/inc/log.php';
session_start();
write_log('logout');
session_destroy();
header("Location: index.php");
$_SESSION['mesgs']['confirm'][] = 'Déconnecté avec succés';
exit();
?>