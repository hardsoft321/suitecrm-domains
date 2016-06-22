<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
require_once 'modules/Domains/Domain.php';
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

    protected function post_save()
    {
        $url = "index.php?module=Domains&action=index";
        $this->set_redirect($url);
    }
}
