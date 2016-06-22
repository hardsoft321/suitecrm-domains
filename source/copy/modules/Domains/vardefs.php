<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['Domain'] = array(
    'audited' => false,
    'fields' => array (
        'domain_name' => array(
            'name' => 'domain_name',
            'vname' => 'LBL_DOMAIN',
            'type' => 'varchar',
            'len' => 50,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 100,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
        ),
    ),
);
