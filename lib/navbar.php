
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Security Website Example</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                </li>
            </ul>
            <span class="navbar-text active">
                <?php
                    if ($_SESSION["loggedin"])
                    {
                        echo "Connecté en tant que <span class=\"text-white\">" . $_SESSION["user"] . "</span> | <a class=\"btn btn-outline-primary\" href=\"logout.php\">Se déconnecter</a>   <a class=\"btn btn-outline-danger\" href=\"changepassword.php\">Changer de mot de passe</a>";
                    }
                    else
                    {
                        echo "<a href=\"auth.php\">Se connecter</a>";
                    }
                ?>
            </span>
        </div>
    </div>
</nav>
