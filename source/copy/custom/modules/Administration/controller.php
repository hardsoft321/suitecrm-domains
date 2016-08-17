<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
require_once('modules/Administration/controller.php');

class CustomAdministrationController extends AdministrationController
{
    public function preProcess()
    {
        require_once 'modules/Domains/DomainReader.php';
        require_once 'modules/Domains/Domain.php';
        $isAdminDomain = Domain::getCurrentDomain() == DomainReader::$ADMIN_DOMAIN;
        if(!$isAdminDomain && in_array($this->action, array(
            'UpgradeWizard',
            'UpgradeWizard_prepare',
            'UpgradeWizard_commit',
        ))) {
            echo 'You must be in admin domain';
            sugar_die('You must be in admin domain');
        }
    }
}
