
<?php
class Logger
{
    public function printLog($message)
    {
        $date = date('d/m/Y H:i:s');
        $log = "[" . $date . "] " . $message . "\n";
        file_put_contents("logs/log.txt", $log, FILE_APPEND);
    }

    public function footerlog($message)
    {
        $_SESSION["footerLog"] = true;
        $_SESSION["footerLogContent"] = $message;
    }
}
?>