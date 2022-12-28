<?php
    require("security.php");
    session_start();
    if (!isset($_SESSION["loggedin"])) $_SESSION["loggedin"] = false;
    $secu = new Security();
?>
