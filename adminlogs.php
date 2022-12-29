<?php
    require("lib/phpheader.php");

    function adminLogs()
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
        if (empty($_POST["clearLogs"])) # The user has just arrived on the page.
        {
            return;
        }
        if (!$secu->isFormValid("clearLogs", []))
        {
            echo "Invalid form.";
            return;
        }

        # --- Functionnal code ---
        file_put_contents("logs/log.txt", "");

    }

    adminLogs();
?>

<!DOCTYPE html>
<html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>Logs</title>
    </head>
    <body>

        <?php
            require_once("lib/navbar.php")
        ?>

        <br/>
        <div class="container">
            <div class="row">
                <div class="column">
                    <h1 class="text-center">Logs</h1>

                    <form action="#" method="post" name="clearform">
                        <?php $secu->insertCSRFField();?>
                        <input type="submit" name="clearLogs" class=" btn btn-warning" value="Effacer les logs (journaux)"><br/><br/>
                    </form>

                    <p>
                        <?php
                            $logs = file_get_contents("logs/log.txt");
                            echo nl2br($logs);
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <?php
            require_once("lib/footer.php")
        ?>

    </body>
</html>
