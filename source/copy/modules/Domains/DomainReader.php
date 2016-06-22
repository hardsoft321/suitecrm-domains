<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 *
 * Класс для определения домена по заголовкам запроса.
 * Работает даже, когда шугаровские файлы не подключены.
 */
class DomainReader
{
    public static $ADMIN_DOMAIN = 'admin';

    /**
     * Возвращает часть url (поддомен) из $_SERVER['HTTP_HOST'].
     * $domainLevel - уровень, на котором искать поддомен.
     * Например, для url admin.example.com поддомен admin находится на уровне 3.
     * Если на этом уровне нет поддомена в url, возвращается домен admin.
     * Имя проверяется на допустимость.
     */
    public static function getDomain($domainLevel)
    {
        if(PHP_SAPI === 'cli') {
            $domain = getenv('SUGAR_DOMAIN');
            return !empty($domain) ? $domain : self::$ADMIN_DOMAIN;
        }
        $domainParts = explode('.', $_SERVER['HTTP_HOST']);
        if(count($domainParts) >= $domainLevel) {
            $domain = $domainParts[count($domainParts) - $domainLevel];
            if(!self::validateDomainName($domain)) {
                throw new Exception('Invalid domain name');
            }
            return $domain;
        }
        if(count($domainParts) == $domainLevel - 1) {
            return self::$ADMIN_DOMAIN;
        }
        throw new Exception('Invalid host');
    }

    /**
     * Возвращает true, если имя допустимо.
     * Допустимы латинские буквы, числа и дефис.
     */
    public static function validateDomainName($domain)
    {
        return !preg_match('#[^A-Z0-9\-]#i', $domain);
    }

    /**
     * Подключает конфиг поддомена.
     * Если нет конфига и домен не admin, выбрасывается исключение.
     */
    public static function requireDomainConfig($domain)
    {
        global $sugar_config;
        if(file_exists("domains/$domain/config.php")) {
            require "domains/$domain/config.php";
        }
        elseif($domain != self::$ADMIN_DOMAIN) {
            throw new Exception('Config not found');
        }
    }
}
