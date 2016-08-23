<?php

global $argv;

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

$params = require __DIR__.'/params.php';

chdir('../../..'); //sugar root
$domainsDirs = glob("domains/{$params['domain_prefix']}*");
$domains = array();
foreach($domainsDirs as $dir) {
    $domain = basename($dir);
    if(preg_match('#^'.$params['domain_prefix'].'[0-9]+$#', $domain)) {
        $domains[] = $domain;
    }
}

if(empty($domains)) {
    echo "No domains with prefix {$params['domain_prefix']}\n";
    return;
}

natsort($domains);
echo "Domains from ".reset($domains)." to ".end($domains)." will be deleted.\n";

if(count($argv) < 2 || $argv[1] != '-f') {
    echo "Use option -f to force deletion\n";
    exit(2);
}

if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once 'modules/Domains/Domain.php';

global $current_user;
$current_user = new User();
$current_user->getSystemUser();

foreach($domains as $domain_name) {
    $domain = new Domain();
    $domain->domain_name = $domain_name;
    $domain->deleteDomain();
}

echo "Done\n";
