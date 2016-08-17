<?php
global $sugar_config;
$sugarVersion = isset($sugar_config['suitecrm_version']) ? 'Suite'.$sugar_config['suitecrm_version'] : $sugar_config['sugar_version'];
$manifest = array(
    'name' => 'domains',
    'acceptable_sugar_versions' => array(),
    'acceptable_sugar_flavors' => array('CE'),
    'author' => 'hardsoft321',
    'description' => 'Поддомены',
    'is_uninstallable' => true,
    'published_date' => '2016-06-09',
    'type' => 'module',
    'version' => '1.1.0',
);
$installdefs = array(
    'id' => 'domains',
    'administration' => array(
        array(
            'from'=>'<basepath>/source/administration/domains.php',
        ),
    ),
    'beans' => array(
        array(
            'module' => 'Domains',
            'class' => 'Domain',
            'path' => 'modules/Domains/Domain.php',
            'tab' => false,
        ),
    ),
    'copy' => array(
        array(
            'from' => '<basepath>/source/copy',
            'to' => '.'
        ),
        array(
           'from' => "<basepath>/source/notupgradesafe/{$sugarVersion}/",
           'to' => '.',
        ),
    ),
    'language' => array(
        array (
            'from' => '<basepath>/source/language/application/ru_ru.lang.php',
            'to_module' => 'application',
            'language' => 'ru_ru',
        ),
        array (
            'from' => '<basepath>/source/language/application/en_us.lang.php',
            'to_module' => 'application',
            'language' => 'en_us',
        ),
        array(
            'from'=> '<basepath>/source/language/modules/Administration/mod_strings_ru_ru.php',
            'to_module'=> 'Administration',
            'language'=>'ru_ru'
        ),
        array(
            'from'=> '<basepath>/source/language/modules/Administration/mod_strings_en_us.php',
            'to_module'=> 'Administration',
            'language'=>'en_us'
        ),
    ),
    'menu'=> array(
        array(
            'from'=> '<basepath>/source/menu/application/Domains.php',
            'to_module'=> 'application',
        ),
    ),
);
