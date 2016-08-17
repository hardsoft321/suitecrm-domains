<?php 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $app_strings;

if(!empty($_SESSION['SUGAR_DOMAIN'])) {
    $module_menu[] = Array("index.php?module=Domains&action=Unmask", $app_strings['LBL_LOGOUT_AS'].$_SESSION['SUGAR_DOMAIN'],"Unmask");
}
