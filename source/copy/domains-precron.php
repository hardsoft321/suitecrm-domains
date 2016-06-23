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

if(file_exists("domains/{$domain}/cache/modules/Schedulers/lastrun")) {
    rename("domains/{$domain}/cache/modules/Schedulers/lastrun", 'cache/modules/Schedulers/lastrun');
}
