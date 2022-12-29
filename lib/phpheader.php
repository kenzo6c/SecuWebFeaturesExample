<?php
    session_start();
    require("security.php");

    $config = [
        "maxNbrOfAttempts" => 5,
        "hashAlgorithm" => PASSWORD_ARGON2ID,
        "CSRFTokenLength" => 64,
        "passwordminlength" => 8,
        "passwordmaxlength" => 64,
        "requireDigit" => true,
        "requireLetter" => true,
        "requireSymbol" => false
    ];

    $secu = new Security($config);
?>
