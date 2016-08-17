<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */

class UpgradeWizardController extends SugarController
{
    public function preProcess()
    {
        require_once 'modules/Domains/DomainReader.php';
        require_once 'modules/Domains/Domain.php';
        $isAdminDomain = Domain::getCurrentDomain() == DomainReader::$ADMIN_DOMAIN;
        if(!$isAdminDomain) {
            echo 'You must be in admin domain';
            sugar_die('You must be in admin domain');
        }
    }
}
