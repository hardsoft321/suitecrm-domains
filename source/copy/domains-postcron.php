<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

chdir(dirname(__FILE__));

$domain = getenv('SUGAR_DOMAIN');
if(empty($domain)) {
    echo "SUGAR_DOMAIN is empty\n";
    exit(2);
}

$toDir = "domains/{$domain}/cache/modules/Schedulers";
if(is_dir("domains/{$domain}") && file_exists('cache/modules/Schedulers/lastrun')) {
    if(!is_dir($toDir)) {
        mkdir($toDir, 0777, true);
    }
    rename('cache/modules/Schedulers/lastrun', "{$toDir}/lastrun");
}
