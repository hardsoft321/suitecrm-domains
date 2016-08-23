<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
require_once 'modules/Domains/Domain.php';
require_once 'modules/Domains/DomainReader.php';
require_once('include/MVC/Controller/SugarController.php');

class DomainsController extends SugarController
{
    public function action_save()
    {
        try {
            $this->bean->createDomain();
        }
        catch(Exception $e) {
            echo $e->getMessage();
            sugar_die("");
        }
    }

    public function action_mask()
    {
        if(!empty($this->bean->domain_name)) {
            if(file_exists("{$this->bean->domain_dir}/config.php")) {
                $sugar_config = array();
                require "{$this->bean->domain_dir}/config.php";
                if(!empty($sugar_config['unique_key'])) {
                    $_SESSION['unique_key'] = $sugar_config['unique_key'];
                }
                $_SESSION['SUGAR_DOMAIN'] = $this->bean->domain_name;
            }
            header('Location: index.php');
        }
    }

    public function action_unmask()
    {
        if(!empty($_SESSION['SUGAR_DOMAIN'])) {
            unset($_SESSION['SUGAR_DOMAIN']);
            $actual_domain = $this->bean->getCurrentDomain();
            if($actual_domain === DomainReader::$ADMIN_DOMAIN) {
                $sugar_config = array();
                require 'config.php';
                if(file_exists("config_override.php")) {
                    require 'config_override.php';
                }
                $domain_dir = DomainReader::getDomainDirByDomainName($actual_domain);
                if(file_exists("{$domain_dir}/config.php")) {
                    require "{$domain_dir}/config.php";
                    if(!empty($sugar_config['unique_key'])) {
                        $_SESSION['unique_key'] = $sugar_config['unique_key'];
                    }
                }
            }
            header('Location: index.php');
        }
    }

    //public function action_delete()
    //{
    //    $this->bean->deleteDomain();
    //}
}
