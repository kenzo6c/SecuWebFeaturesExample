<?php
    require("security.php");
    session_start();

    $config = [
        "maxNbrOfAttempts" => 5,
        "hashAlgorithm" => PASSWORD_ARGON2ID,
    ];

    $secu = new Security($config["maxNbrOfAttempts"]);
?>
