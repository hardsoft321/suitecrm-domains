<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
require_once 'modules/Domains/DomainReader.php';

global $argv;

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

if(count($argv) < 3) {
    echo "Domain must be specified at first argument\n";
    echo "Command must be specified at second argument\n";
    exit(2);
}

$domain = $argv[1]; //экранировать не надо, так как pipe (|) экранируется
$command = $argv[2]; //экранировать не надо, так как pipe (|) экранируется

chdir(dirname(__FILE__));

if(!DomainReader::validateDomainName($domain)) {
    echo "Invalid domain $domain\n";
    exit(4);
}

echo "\nProcessing domain $domain...\n";
putenv("SUGAR_DOMAIN=$domain");
$output = array();
$return_var = null;
exec($command, $output, $return_var);
putenv("SUGAR_DOMAIN=");
foreach($output as $str) {
    echo $str."\n";
}
if($return_var) {
    echo "ERROR: Non-zero return. Aborted.\n";
    exit(3);
}
