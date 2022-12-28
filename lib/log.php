
<?php
class Logger
{
    public function printlog($message)
    {
        $date = date('d/m/Y H:i:s');
        $log = "[" . $date . "] " . $message . "\n";
        file_put_contents("logs/log.txt", $log, FILE_APPEND);
    }
}
?>