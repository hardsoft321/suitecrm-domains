<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/ListView/ListViewData.php');

class DomainsListViewData extends ListViewData
{
    function getListViewData($seed, $where, $offset=-1, $limit = -1, $filter_fields=array(),$params=array(),$id_field = 'id',$singleSelect=true)
    {
        $folders = glob('domains/*');
        natsort($folders);
        $data = array();
        foreach($folders as $dir) {
            $domain = basename($dir);
            $data[] = array(
                'DOMAIN_NAME' => $domain,
            );
        }
        $sugarData = array(
            'data' => $data,
            'pageData' => array(
                'offsets' => array(
                    'current' => 0,
                    'next' => -1,
                    'prev' => -1,
                    'end' => 0,
                    'total' => count($data),
                    'totalCounted' => 1,
                ),
                'bean' => array(
                    'objectName' => $seed->object_name,
                    'moduleDir' => $seed->module_dir,
                ),
            )
        );
        return $sugarData;
    }
}
