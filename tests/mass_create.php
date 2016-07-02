<?php

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

if(count($argv) < 2) {
    echo "Count to generate must be specified. E.g. php mass_create.php 10\n";
    exit(2);
}

$count_to_generate = (int)$argv[1];

$params = require __DIR__.'/params.php';

chdir('../../..'); //sugar root

$startIndex = 1;
$domainsDirs = glob("domains/{$params['domain_prefix']}*");
$domains = array();
foreach($domainsDirs as $dir) {
    $domain = basename($dir);
    if(preg_match('#^'.$params['domain_prefix'].'[0-9]+$#', $domain)) {
        $domains[] = $domain;
    }
}
if(!empty($domains)) {
    natsort($domains);
    $lastDomain = end($domains);
    if(preg_match('#^'.$params['domain_prefix'].'([0-9]+)$#', $lastDomain, $matches)) {
        $startIndex = $matches[1] + 1;
    }
}



if(!defined('sugarEntry'))define('sugarEntry', true);
require_once('include/entryPoint.php');
require_once 'modules/Domains/Domain.php';

for($i = 0; $i < $count_to_generate; $i++) {
    $domain = new Domain();
    $domain->domain_name = $params['domain_prefix'].($startIndex + $i);
    $domain->name = $params['domain_prefix'].($startIndex + $i);
    $domain->createDomain();
}

echo "Done\n";
