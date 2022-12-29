

<?php
    $display = !empty($_SESSION["footerLog"]) && $_SESSION["footerLog"] === true;
    $CSSclass = "text-center text-lg-start";

    if ($display)
    {
        $CSSclass = "bg-warning" . $CSSclass;
    }
?>
<footer class=<?=$CSSclass?>>
    <div class="text-center p-3">
        <?php
            if ($display)
            {
                echo "/!\\" . $_SESSION["footerLogContent"];

                $_SESSION["footerLog"] = false;
            }
        ?>
    </div>
</footer>

<script src="js/bootstrap.min.js"></script>