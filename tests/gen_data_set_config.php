<?php

$CSV_DELIMITER = ',';
$CSV_EOL = "\n";

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

$params = require __DIR__.'/params.php';

chdir('../../..'); //sugar root
$domainsDirs = glob("domains/{$params['domain_prefix']}*");
$domainsData = array();
foreach($domainsDirs as $dir) {
    $domain = basename($dir);
    if(preg_match('#^'.$params['domain_prefix'].'[0-9]+$#', $domain)) {
        $sugar_config = array();
        require "domains/$domain/config.php";
        $domainsData[$domain] = array(
            'domain' => $domain,
            'crm_host_name' => $sugar_config['host_name'],
        );
    }
}

if(empty($domainsData)) {
    echo "No domains with prefix {$params['domain_prefix']}\n";
    exit(2);
}

$domainsNames = array_keys($domainsData);
natsort($domainsNames);
$domainsDataSorted = array();
foreach($domainsNames as $domain) {
    $domainsDataSorted[] = $domainsData[$domain];
}

chdir(__DIR__);

echo "Creating file domains.csv\n";
$domainsFile = fopen('domains.csv', 'w');
if(!$domainsFile) {
    echo "Unable to create file\n";
    exit(3);
}

foreach($domainsDataSorted as $domainData) {
    $lk_host_name = preg_replace('/^crm\./', '', $domainData['crm_host_name']);
    fwrite($domainsFile, $lk_host_name);
    fwrite($domainsFile, $CSV_DELIMITER);
    fwrite($domainsFile, $domainData['domain']);
    fwrite($domainsFile, $CSV_EOL);
}
fclose($domainsFile);
