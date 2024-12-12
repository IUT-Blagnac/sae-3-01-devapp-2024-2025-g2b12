<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}
?>