<?php

require_once('sugar_version.php');
require_once('suitecrm_version.php');

global $sugar_config;
//$sugar_config['default_max_tabs'] = 10;
//$sugar_config['suitecrm_version'] = $suitecrm_version;
//$sugar_config['sugar_version'] = $sugar_version;
//$sugar_config['sugarbeet'] = false;
//
//ksort($sugar_config);
//write_array_to_file('sugar_config', $sugar_config, 'config.php');

require_once('modules/Administration/updater_utils.php');
set_CheckUpdates_config_setting('manual');


//require_once('install/suite_install/AdvancedOpenSales.php');
//install_aos();

//require_once('install/suite_install/AdvancedOpenPortal.php');
//install_aop();

//require_once('install/suite_install/AdvancedOpenDiscovery.php');
//install_aod();

require_once('install/suite_install/AdvancedOpenEvents.php');
install_aoe();

//require_once('install/suite_install/Projects.php');
//install_projects();

//require_once('install/suite_install/Reschedule.php');
//install_reschedule();

//require_once('install/suite_install/SecurityGroups.php');
//install_ss();

require_once('install/suite_install/GoogleMaps.php');
install_gmaps();

//require_once('install/suite_install/Social.php');
//install_social();

//require_once('modules/Administration/QuickRepairAndRebuild.php');
//$actions = array('clearAll');
//$randc = new RepairAndClear();
//$randc->repairAndClearAll($actions, array(translate('LBL_ALL_MODULES')), true,false);
