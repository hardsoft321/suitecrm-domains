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
        'admin_email' => array(
            'name' => 'admin_email',
            'vname' => 'LBL_ADMIN_EMAIL',
            'type' => 'varchar',
            'len' => 255,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
        ),
    ),
);
