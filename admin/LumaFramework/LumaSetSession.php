<?php
session_start();

header('Content-Type: application/json');
if (isset($_POST['clearSession']) && $_POST['clearSession'] === 'true') {
    session_unset();
}
foreach ($_POST['sessionData'] as $key => $value) {
    $_SESSION[$key] = $value;
}
echo json_encode(["success" => true, "clearSession" => $_POST['clearSession']]);
?>
