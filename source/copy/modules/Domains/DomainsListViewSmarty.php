<?php
require_once 'include/ListView/ListViewSmarty.php';
require_once 'modules/Domains/DomainsListViewData.php';

class DomainsListViewSmarty extends ListViewSmarty
{
    function __construct()
    {
        parent::__construct();
        $this->lvd = new DomainsListViewData();
        $this->lvd->lv = $this;
        $this->multiSelect = false; // чек-бокс убираем, так как не работает
    }
}
