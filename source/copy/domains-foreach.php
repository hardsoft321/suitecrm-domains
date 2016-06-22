<?php
global $argv;

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

if(count($argv) < 2) {
    echo "Command must be specified at first argument\n";
    exit(2);
}

$command = escapeshellcmd($argv[1]);

chdir(dirname(__FILE__));

$folders = glob('domains/*');
natsort($folders);
foreach($folders as $dir) {
    $domain = basename($dir);
    echo "\nProcessing domain $domain...\n";
    putenv("SUGAR_DOMAIN=$domain");
    $output = array();
    $return_var = null;
    exec($command, $output, $return_var);
    foreach($output as $str) {
        echo $str."\n";
    }
    if($return_var) {
        echo "ERROR: Non-zero return. Aborted.\n";
        exit(3);
    }
}
