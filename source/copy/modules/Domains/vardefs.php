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
            'inline_edit' => 0,
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 50,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
            'inline_edit' => 0,
        ),
        'title_name' => array(
            'name' => 'title_name',
            'vname' => 'LBL_TITLE_NAME',
            'type' => 'varchar',
            'len' => 100,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
            'inline_edit' => 0,
        ),
        'admin_email' => array(
            'name' => 'admin_email',
            'vname' => 'LBL_ADMIN_EMAIL',
            'type' => 'varchar',
            'len' => 255,
            'source'=>'non-db',
            'required' => true,
            'reportable'=>false,
            'inline_edit' => 0,
        ),
        'domain_dir' => array(
            'name' => 'domain_dir',
            'vname' => 'LBL_DOMAIN_DIR',
            'type' => 'varchar',
            'source'=>'non-db',
            'reportable'=>false,
            'inline_edit' => 0,
        ),
        'sbstatus' => array(
            'name' => 'sbstatus',
            'vname' => 'LBL_SBSTATUS',
            'type' => 'varchar',
            'source'=>'non-db',
            'reportable'=>false,
            'inline_edit' => 0,
        ),
    ),
);
