<?php
class Domains_DBManager
{
    /**
     * @author include/database/DBManager
     */
    public static function getValidDBName($name, $ensureUnique = '', $type = 'column', $force = false)
    {
        /**
         * TODO: Захардкодено для mysql
         * добавил user
         */
        $maxNameLengths = array(
            'table' => 64,
            'column' => 64,
            'index' => 64,
            'alias' => 256,
            'user' => 16,
        );

        if(is_array($name)) {
            $result = array();
            foreach($name as $field) {
                $result[] = self::getValidDBName($field, $ensureUnique, $type);
            }
            return $result;
        } else {
            if(strchr($name, ".")) {
                // this is a compound name with dots, handle separately
                $parts = explode(".", $name);
                if(count($parts) > 2) {
                    // some weird name, cut to table.name
                    array_splice($parts, 0, count($parts)-2);
                }
                $parts = self::getValidDBName($parts, $ensureUnique, $type, $force);
                return join(".", $parts);
            }
            // first strip any invalid characters - all but word chars (which is alphanumeric and _)
            $name = preg_replace( '/[^\w]+/i', '', $name ) ;
            $len = strlen( $name ) ;
            $maxLen = empty($maxNameLengths[$type]) ? $maxNameLengths[$type]['column'] : $maxNameLengths[$type];
            if ($len <= $maxLen && !$force) {
                return strtolower($name);
            }
            if ($ensureUnique) {
                $md5str = md5($name.$ensureUnique);
                $tail = substr ( $name, -5) ;
                $temp = substr($md5str , 0, $maxLen - 10);
                $result = substr( $name, 0, 5) . $temp . $tail ;
            }
            else {
                $result = substr( $name, 0, 11) . substr( $name, 11 - $maxLen);
            }

            return strtolower( $result ) ;
        }
    }
}
