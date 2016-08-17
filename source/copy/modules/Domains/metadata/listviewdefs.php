<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['Domains'] = array(
    'DOMAIN_NAME' => array(
        'width' => '10',
        'label' => 'LBL_DOMAIN_NAME',
        'default' => true,
        'sortable' => false,
        'link' => true,
        'id' => 'DOMAIN_NAME',
        'customCode' => '<a href="index.php?module=Domains&action=DetailView&record={$DOMAIN_NAME}">{$DOMAIN_NAME}</a>',
    ),
);
