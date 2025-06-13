<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected'])) {
    $_SESSION['annee'] = $_POST['selected'];
    session_write_close();
    echo "Session updated to: " . $_SESSION['annee'];
} else {
    http_response_code(400);
    echo "Invalid request";
}
