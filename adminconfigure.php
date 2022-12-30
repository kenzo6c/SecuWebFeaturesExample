<?php
    require("lib/phpheader.php");

    function adminConfigure()
    {
        # --- Global variables ---
        global $secu;
        global $config;

        # --- Guard Clauses ---
        if (!$secu->hasAccess("root"))
        {
            header("Location: noaccess.php");
            exit();
        }
        if (empty($_POST["submit"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValidLog("newconfig", ["attemptsWaitTime", "maxAttemptsAccount", "hashAlgorithm", "CSRFTokenLength", "passwordminlength", "passwordmaxlength"]))
        {
            return;
        }

        # --- Functionnal code ---
        $newconfig = $_POST["newconfig"];

        $previous_max_attempts = $config["maxAttemptsAccount"];

        $config = [
            "attemptsWaitTime" => intval($newconfig["attemptsWaitTime"]),
            // "maxAttemptsSession" => intval($newconfig["maxAttemptsSession"]),
            "maxAttemptsAccount" => intval($newconfig["maxAttemptsAccount"]),
            "hashAlgorithm" => $newconfig["hashAlgorithm"],
            "CSRFTokenLength" => intval($newconfig["CSRFTokenLength"]),
            "passwordminlength" => intval($newconfig["passwordminlength"]),
            "passwordmaxlength" => intval($newconfig["passwordmaxlength"]),
            "requireDigit" => isset($newconfig["requireDigit"]) && $newconfig["requireDigit"] === "1" ? true : false,
            "requireLetter" => isset($newconfig["requireLetter"]) && $newconfig["requireLetter"] === "1" ? true : false,
            "requireSymbol" => isset($newconfig["requireSymbol"]) && $newconfig["requireSymbol"] === "1" ? true : false
        ];
        file_put_contents("data/config.json", json_encode($config));

        $accounts = json_decode(file_get_contents("data/accounts.json"), true);
        foreach ($accounts as &$account)
        {
            if ($account["attempts_left"] > $config["maxAttemptsAccount"])
            {
                $account["attempts_left"] = $config["maxAttemptsAccount"];
            }
        }
        file_put_contents("data/accounts.json", json_encode($accounts));

        if (!empty($_SESSION["waitingTime"]))
        {
            $_SESSION["waitingTime"] = 0;
        }

        $secu->logger->footerLog("La configuration a été changée.", "success");
        $secu->logger->printLog("Configuration has been updated by \"" . $_SESSION["user"] . "\".");

    }

    adminConfigure();
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Configuration de la politique de sécurité</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">

            <div class="row">
                <div class="column">

                    <h1 class="text-center">Configurer la politique de sécurité</h1>

                    <p>
                        <a href="admin.php">Retourner au panneau d'administration</a><br/>
                    </p>

                    <form action="#" method="post" name="adminform">

                        <label for="attemptsWaitTime" class="form-label">Temps d'attente entre deux essais :</label>
                        <input type="number" name="newconfig[attemptsWaitTime]" id="attemptsWaitTime" class="form-control" value="<?= $config["attemptsWaitTime"]?>"><br/>

                        <!-- <label for="maxAttemptsSession" class="form-label">Nombre maximal d'essais par session :</label>
                        <input type="number" name="newconfig[maxAttemptsSession]" id="maxAttemptsSession" class="form-control" value="<?= $config["maxAttemptsSession"]?>"><br/>
 -->

                        <label for="maxAttemptsAccount" class="form-label">Nombre maximal d'essais par compte :</label>
                        <input type="number" name="newconfig[maxAttemptsAccount]" id="maxAttemptsAccount" class="form-control" value="<?= $config["maxAttemptsAccount"]?>"><br/>

                        <label for="hashAlgorithm" class="form-label">Algorithme de hachage :</label>
                        <select name="newconfig[hashAlgorithm]" id="hashAlgorithm" class="form-select">
                            <option value= <?=PASSWORD_BCRYPT?> <?= $config["hashAlgorithm"] === PASSWORD_BCRYPT ? "selected" : ""?>>PASSWORD_BCRYPT</option>
                            <option value= <?=PASSWORD_ARGON2I?> <?= $config["hashAlgorithm"] === PASSWORD_ARGON2I ? "selected" : ""?>>PASSWORD_ARGON2I</option>
                            <option value= <?=PASSWORD_ARGON2ID?> <?= $config["hashAlgorithm"] === PASSWORD_ARGON2ID ? "selected" : ""?>>PASSWORD_ARGON2ID</option>
                        </select><br/>

                        <label for="CSRFTokenLength" class="form-label">Longueur du token CSRF :</label>
                        <input type="number" name="newconfig[CSRFTokenLength]" id="CSRFTokenLength" class="form-control" value="<?= $config["CSRFTokenLength"]?>"><br/>

                        <label for="passwordminlength" class="form-label">Longueur minimale du mot de passe :</label>
                        <input type="number" name="newconfig[passwordminlength]" id="passwordminlength" class="form-control" value="<?= $config["passwordminlength"]?>"><br/>

                        <label for="passwordmaxlength" class="form-label">Longueur maximale du mot de passe :</label>
                        <input type="number" name="newconfig[passwordmaxlength]" id="passwordmaxlength" class="form-control" value="<?= $config["passwordmaxlength"]?>"><br/>

                        <label for="requireDigit" class="form-check-label">Exiger un chiffre dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireDigit]" id="requireDigit" class="form-check-input" value="1" <?= $config["requireDigit"] ? "checked" : ""?>><br/>
                        <br/>

                        <label for="requireLetter" class="form-check-label">Exiger une lettre dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireLetter]" id="requireLetter" class="form-check-input" value="1" <?= $config["requireLetter"] ? "checked" : ""?>><br/>
                        <br/>

                        <label for="requireSymbol" class="form-check-label">Exiger un symbole dans le mot de passe :</label>
                        <input type="checkbox" name="newconfig[requireLetter]" id="requireSymbol" class="form-check-input" value="1" <?= $config["requireSymbol"] ? "checked" : ""?>><br/>
                        <br/>

                        <?php $secu->insertCSRFField(); ?>

                        <input type="submit" name="submit" class=" btn btn-warning" value="Mettre à jour la configuration"><br/><br/>
                    </form>

                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
