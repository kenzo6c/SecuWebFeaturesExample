

<?php
    $display = !empty($_SESSION["footerLog"]) && $_SESSION["footerLog"] === true;
    $CSSclass = "text-center text-lg-start";

    if ($display)
    {
        if ($_SESSION["footerLogLevel"] === "success")
            $CSSclass = "bg-success text-white " . $CSSclass;
        else if ($_SESSION["footerLogLevel"] === "danger")
            $CSSclass = "bg-danger text-white " . $CSSclass;
        else if ($_SESSION["footerLogLevel"] === "warning")
            $CSSclass = "bg-warning text-dark " . $CSSclass;
    }
?>
<br/><br/>

<footer class="<?=$CSSclass?>">
    <div class="text-center p-3">
        <?php
            if ($display)
            {
                if ($_SESSION["footerLogLevel"] === "success")
                    echo "✅ ";
                else if ($_SESSION["footerLogLevel"] === "danger")
                    echo "❌ ";
                else if ($_SESSION["footerLogLevel"] === "warning")
                    echo "⚠️ ";
                echo $_SESSION["footerLogContent"];

                $_SESSION["footerLog"] = false;
                $_SESSION["footerLogContent"] = "";
                $_SESSION["footerLogLevel"] = "";
            }
        ?>
    </div>
</footer>

<script src="js/bootstrap.min.js"></script>