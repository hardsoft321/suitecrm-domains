<?php
require_once 'modules/Domains/DomainReader.php';
require_once 'include/dir_inc.php';
require_once 'include/utils/array_utils.php';
require_once 'install/install_utils.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
class Domain extends SugarBean
{
    public $object_name = 'Domain';
    public $module_dir = 'Domains';

    public static $ADMIN_DOMAIN = 'admin';
    public $domain_name;

    public $additional_meta_fields = array( //против Notice в EditView2.php
        'id' => array (
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'source'=>'non-db',
            'value' => '',
        ),
    );

    function ACLAccess($view,$is_owner='not_set')
    {
        $domainLevel = !empty($GLOBALS['sugar_config']['domain_level']) ? $GLOBALS['sugar_config']['domain_level'] : 3;
        return $GLOBALS['current_user']->isAdmin() && DomainReader::getDomain($domainLevel) == DomainReader::$ADMIN_DOMAIN;
    }

    /**
     * Дополнительная проверка на имя домена.
     * Возвращает 0, если имя допустимо.
     */
    public function validateDomainName2($domain)
    {
        $fields = $this->getFieldDefinitions();
        if(strlen($domain) > $fields['domain_name']['len']) {
            return 1;
        }
        return 0;
    }

    public function createDomain()
    {
        if(empty($this->domain_name) || $this->domain_name == self::$ADMIN_DOMAIN || is_dir("domains/{$this->domain_name}")) {
            throw new Exception("Domain {$this->domain_name} is busy");
        }
        if(!DomainReader::validateDomainName($this->domain_name)) {
            throw new Exception("Invalid domain name '{$this->domain_name}'");
        }
        if($this->validateDomainName2($this->domain_name) !== 0) {
            throw new Exception("Invalid domain name '{$this->domain_name}'!");
        }
        $db_prefix = !empty($GLOBALS['sugar_config']['domain_db_prefix']) ? $GLOBALS['sugar_config']['domain_db_prefix'] : '';
        $setup_db_database_name = $db_prefix.$this->domain_name;
        $setup_db_sugarsales_user = $setup_db_database_name;
        if($this->db->getOne("SELECT 1 FROM mysql.user WHERE user = '$setup_db_sugarsales_user'")) {
            throw new Exception("Database user already exists: $setup_db_sugarsales_user");
        }
        if($this->db->dbExists($setup_db_database_name)) {
            throw new Exception("Database already exists: $setup_db_database_name");
        }

        $fromaddress = $this->db->getOne("SELECT value FROM config WHERE category = 'notify' AND name = 'fromaddress'");
        $outboundEmail = $this->db->fetchOne("SELECT mail_smtpserver, mail_smtpport, mail_smtpauth_req, mail_smtpuser, mail_smtppass FROM outbound_email WHERE user_id = 1 AND deleted = 0 AND name = 'system' AND type = 'system'");

        mkdir_recursive("domains/{$this->domain_name}");
        make_writable("domains/{$this->domain_name}");
        $domainConfigFile = "domains/{$this->domain_name}/config.php";
        touch($domainConfigFile);
        if(!(make_writable($domainConfigFile)) || !(is_writable($domainConfigFile))) {
            throw new Exception("Unable to write to the $domainConfigFile file. Check the file permissions");
        }
        $dbUserPassword = self::generatePassword(10);
        $domainLevel = !empty($GLOBALS['sugar_config']['domain_level']) ? $GLOBALS['sugar_config']['domain_level'] : 3;
        $overideString = "<?php\n"
                .'// created: ' . date('Y-m-d H:i:s') . "\n";
        $overrideArray = array(
            'dbconfig' => array(
                'db_name' => $setup_db_database_name,
                'db_user_name' => $setup_db_sugarsales_user,
                'db_password' => $dbUserPassword,
            ),
            'http_referer' => array('list' => array($this->domain_name.'.'.$GLOBALS['sugar_config']['host_name'])),
            'log_dir' => "domains/{$this->domain_name}",
            'log_file' => 'suitecrm.log',
            'site_url' => self::buildUrlForDomain($GLOBALS['sugar_config']['site_url'], $this->domain_name, $domainLevel),
            'unique_key' => md5(create_guid()),
            'upload_dir' => "domains/{$this->domain_name}/upload/",
            'cache_dir' => "domains/{$this->domain_name}/cache/", //кэш нужен разный, как минимум, для файла крона cache/modules/Schedulers/lastrun
        );
        foreach($overrideArray as $key => $val) {
            if (/*in_array($key, $this->allow_undefined) ||*/ isset ($sugar_config[$key])) {
                if (is_string($val) && strcmp($val, 'true') == 0) {
                    $val = true;
                    //$this->config[$key] = $val;
                }
                if (is_string($val) && strcmp($val, 'false') == 0) {
                    $val = false;
                    //$this->config[$key] = false;
                }
            }
            $overideString .= override_value_to_string_recursive2('sugar_config', $key, $val);
        }
        $fp = sugar_fopen($domainConfigFile, 'w');
        fwrite($fp, $overideString);
        fclose($fp);

        global $beanFiles;
        global $dictionary;
        global $mod_strings;
        $mod_strings = self::return_install_mod_strings();
        $mod_strings = sugarLangArrayMerge($GLOBALS['app_strings'], $GLOBALS['mod_strings']);
        $_SESSION['setup_db_create_database'] = true;
        $_SESSION['setup_db_type'] = $GLOBALS['sugar_config']['dbconfig']['db_type'];
        $_SESSION['setup_db_manager'] = $GLOBALS['sugar_config']['dbconfig']['db_manager'];
        $_SESSION['setup_db_host_name'] = $GLOBALS['sugar_config']['dbconfig']['db_host_name'];
        $_SESSION['setup_db_admin_user_name'] = $GLOBALS['sugar_config']['dbconfig']['db_user_name'];
        $_SESSION['setup_db_admin_password'] = $GLOBALS['sugar_config']['dbconfig']['db_password'];
        $_SESSION['setup_db_host_instance'] = $GLOBALS['sugar_config']['dbconfig']['db_host_instance'];
        $_SESSION['setup_db_port_num'] = $GLOBALS['sugar_config']['dbconfig']['db_port'];
        $GLOBALS['setup_db_database_name'] = $_SESSION['setup_db_database_name'] = $setup_db_database_name;
        $_SESSION['setup_db_create_sugarsales_user'] = true;
        $GLOBALS['setup_site_host_name'] = $GLOBALS['sugar_config']['dbconfig']['db_host_name'];
        $GLOBALS['setup_db_sugarsales_user'] = $_SESSION['setup_db_sugarsales_user'] = $setup_db_sugarsales_user;
        $GLOBALS['setup_db_sugarsales_password'] = $_SESSION['setup_db_sugarsales_password'] = $dbUserPassword;
        $_SESSION['setup_db_drop_tables'] = false;
        $GLOBALS['create_default_user'] = false; //пользователь по умолчанию, помимо admin - не создавать
        $GLOBALS['setup_site_admin_user_name'] = $_SESSION['setup_site_admin_user_name'] = 'admin';
        $GLOBALS['setup_site_admin_password'] = $_SESSION['setup_site_admin_password'] = $dbUserPassword;
        $_SESSION['setup_site_sugarbeet_automatic_checks'] = false;
        $_SESSION['setup_system_name'] = $this->name;
        $_SESSION['demoData'] = 'no';
        $currentDbConfig = $GLOBALS['sugar_config']['dbconfig'];
        $install_script = true;

        require 'modules/Domains/install/performSetup.php';
        $db = DBManagerFactory::getInstance(); //это база поддомена
        if($fromaddress) {
            $domainFromaddress = self::buildEmailForDomain($fromaddress, $this->domain_name, $domainLevel);
            if($domainFromaddress) {
                $db->query("UPDATE config SET value = '".$db->quote($domainFromaddress)."' WHERE category = 'notify' AND name = 'fromaddress'");
            }
        }
        if($outboundEmail) {
            $oe = new OutboundEmail();
            $oe = $oe->getSystemMailerSettings();
            $oe->mail_smtpserver = $outboundEmail['mail_smtpserver'];
            $oe->mail_smtpport = $outboundEmail['mail_smtpport'];
            $oe->mail_smtpauth_req = $outboundEmail['mail_smtpauth_req'];
            $oe->mail_smtpuser = $outboundEmail['mail_smtpuser'];
            $oe->mail_smtppass = $outboundEmail['mail_smtppass'];
            $oe->save();
        }
        //TODO: возможно прочие настройки из таблицы config нужно скопировать

        $acc = BeanFactory::newBean('Accounts');
        $acc->name = $this->name;
        $acc->save();
        $db->query("UPDATE accounts SET id = 'main' WHERE id = '{$acc->id}'"); //id контрагента = main для главной записи

        unset($_SESSION['setup_db_type']);
        unset($_SESSION['setup_db_manager']);
        unset($_SESSION['setup_db_host_name']);
        unset($_SESSION['setup_db_admin_password']);
        unset($_SESSION['setup_db_create_database']);
        unset($_SESSION['setup_db_create_sugarsales_user']);
        unset($_SESSION['setup_db_sugarsales_user']);
        unset($_SESSION['setup_db_admin_user_name']);
        unset($_SESSION['setup_db_sugarsales_password']);
        unset($GLOBALS['setup_db_sugarsales_password']);
        unset($_SESSION['setup_db_drop_tables']);
        unset($GLOBALS['setup_site_admin_password']);
        unset($_SESSION['setup_db_host_instance']);
        unset($_SESSION['setup_db_database_name']);
        unset($_SESSION['setup_db_port_num']);
        unset($_SESSION['setup_site_admin_password']);
        $GLOBALS['sugar_config']['dbconfig'] = $currentDbConfig;
        unset($GLOBALS['sugar_config']['dbconfig']);
        unset($_SESSION['setup_site_admin_user_name']);
        unset($_SESSION['setup_site_sugarbeet_automatic_checks']);
        unset($_SESSION['setup_system_name']);
        unset($_SESSION['demoData']);
        DBManagerFactory::disconnectAll();
        $GLOBALS['db'] = DBManagerFactory::getInstance();
    }

    public static function generatePassword($length)
    {
        $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alphabetLength = mb_strlen($alphabet);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= mb_substr($alphabet, rand(0, $alphabetLength - 1), 1, 'UTF-8');
        }
        return $str;
    }

    protected function buildUrlForDomain($site_url, $domain, $domainLevel)
    {
        if(preg_match("#^http(s?)\://([^/]+)(.*)$#", $site_url, $matches)) {
            $host = $matches[2];
            $parts = explode('.', $host);
            $newHost = implode('.', array_merge(array($domain), array_slice($parts, -($domainLevel-1))));
            return 'http'.$matches[1].'://'.$newHost.$matches[3];
        }
        return false;
    }

    protected function buildEmailForDomain($email, $domain, $domainLevel)
    {
        if(preg_match("#^(.+)@(.+)$#", $email, $matches)) {
            $host = $matches[2];
            $parts = explode('.', $host);
            $newHost = implode('.', array_merge(array($domain), array_slice($parts, -($domainLevel-1))));
            return $matches[1].'@'.$newHost;
        }
        return false;
    }

    protected static function return_install_mod_strings()
    {
        global $sugar_config;
        $lang = isset($sugar_config['default_language']) ? $sugar_config['default_language'] : 'en_us';
        var_dump($lang);

        $mod_strings = array();
        if (file_exists("install/language/$lang.lang.php")) {
            include "install/language/$lang.lang.php";
            $GLOBALS['log']->info("Found language file: $lang.lang.php");
        }
        return $mod_strings;
    }
}
