<?php
namespace WRO;

class Logger {    
    public static function Write($msg) {
        $date = new \DateTime();
        $file = "wp-content/plugins/WoWRaidOrganizer/log.txt";

        file_put_contents($file, "[" . $date->format('m-d-Y H:i:s') . "] " . $msg . "\n", FILE_APPEND);
    }
};