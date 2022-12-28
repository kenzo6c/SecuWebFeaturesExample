<?php
    session_start();
    require("security.php");

    $config = [
        "maxNbrOfAttempts" => 5,
        "hashAlgorithm" => PASSWORD_ARGON2ID,
        "CSRFTokenLength" => 64
    ];

    $secu = new Security($config["maxNbrOfAttempts"], $config["CSRFTokenLength"]);
?>
