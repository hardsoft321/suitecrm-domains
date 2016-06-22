<?php

$admin_options_defs = array();
$admin_options_defs['Domains']['index'] = array(
    'Domains',
    'LBL_DOMAINS_TITLE',
    'LBL_DOMAINS_DESC',
    './index.php?module=Domains&action=index',
);

$admin_options_defs['Domains']['create'] = array(
    'DomainsCreate',
    'LBL_CREATE_DOMAIN_TITLE',
    'LBL_CREATE_DOMAIN_DESC',
    './index.php?module=Domains&action=EditView',
);

$admin_group_header[] = array(
    'LBL_DOMAINS_TITLE',
    '',
    false,
    $admin_options_defs,
    '',
);
