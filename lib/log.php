
<?php
class Logger
{
    public function printLog($message)
    {
        $date = date('d/m/Y H:i:s');
        $log = "[" . $date . "] " . $message . "\n";
        file_put_contents("logs/log.txt", $log, FILE_APPEND);
    }

    public function footerLog($message, $level="warning")
    {
        $_SESSION["footerLog"] = true;
        if (empty($_SESSION["footerLogContent"]))
        {
            $_SESSION["footerLogContent"] = $message;
        }
        else
        {
            $_SESSION["footerLogContent"] .= "<br/>" . $message;
        }
        $_SESSION["footerLogLevel"] = $level;
    }
}
?>