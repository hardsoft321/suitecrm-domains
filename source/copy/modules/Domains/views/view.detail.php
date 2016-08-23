<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
require_once('include/MVC/View/views/view.detail.php');

class DomainsViewDetail extends ViewDetail
{
    function display()
    {
        $this->bean->gatherInfo();
        parent::display();
    }
}
