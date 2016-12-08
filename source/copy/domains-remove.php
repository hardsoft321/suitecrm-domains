<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */

if(!defined('sugarEntry'))define('sugarEntry', true);
chdir(dirname(__FILE__));
require_once('include/entryPoint.php');
require_once 'modules/Domains/DomainReader.php';
require_once 'modules/Domains/Domain.php';

global $argv;

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

if(count($argv) < 2) {
    echo "Domain must be specified at first argument\n";
    exit(2);
}

$domain_name = $argv[1];

if(!DomainReader::validateDomainName($domain_name)) {
    echo "Invalid domain $domain_name\n";
    exit(4);
}

echo "\nRemoving domain $domain_name...\n"; //TODO: ask confirmation

$domain = new Domain();
$domain->domain_name = $domain_name;
try {
    $domain->deleteDomain();
    echo "Done\n";
}
catch(Exception $ex) {
    echo $ex->getMessage()."\n";
    exit(3);
}
