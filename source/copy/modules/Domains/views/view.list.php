<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.list.php');
require_once('modules/Domains/DomainsListViewSmarty.php');

class DomainsViewList extends ViewList
{
    function preDisplay()
    {
        $this->lv = new DomainsListViewSmarty();
    }
}
