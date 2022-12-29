<?php
    session_start();
    require("security.php");

    $config = json_decode(file_get_contents("data/config.json"), true);

    $secu = new Security($config);
?>
