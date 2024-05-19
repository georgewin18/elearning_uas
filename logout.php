<?php

session_start();

if (isset($_SESSION['user_logged_in'])) {
    unset($_SESSION['user_logged_in']);
}

header('Location: login.php');

?>