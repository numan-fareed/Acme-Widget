<?php
session_start();
if (isset($_POST['clear_session'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php"); // Redirect to index.php or any other page
    exit();
}
