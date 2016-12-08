<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package domains
 */
if(!defined('sugarEntry'))define('sugarEntry', true);
chdir(dirname(__FILE__));
require_once('include/entryPoint.php');

require_once 'modules/Domains/DomainReader.php';
require_once 'modules/Domains/Domain.php';

global $argv;

if(PHP_SAPI !== 'cli') {
    echo "CLI only\n";
    exit(1);
}

$subjectCount = 1;
$allowedOptions = array(
    'title:',
);
$example = 'php domains-create.php --title="My domain 1" domain1';

try {
    list($keywords, $options) = getArgvParams($subjectCount, $allowedOptions);
}
catch(Exception $ex) {
    echo $ex->getMessage()."\n";
    echo "Example: {$example}\n";
    exit(2);
}

$domain = new Domain();
$domain->domain_name = reset($keywords);

$domain->title_name = !empty($options['title']) ? $options['title'] : '';

echo "Creating domain {$domain->domain_name}...\n";

try {
    $domain->createDomain();
}
catch(Exception $ex) {
    echo $ex->getMessage()."\n";
    exit(3);
}

/**********************************************************************/

function getArgvParams($subjectCount, $allowedOptions)
{
    global $argv;
    $options = array();
    $subjects = array();
    for($i = 1; $i < count($argv); $i++) {
        if($argv[$i][0] == '-') {
            $optPair = explode("=", ltrim($argv[$i], '-'));
            $name = $optPair[0];
            if(in_array($name.':', $allowedOptions)) {
                if(count($optPair) < 2) {
                    throw new Exception("Value must be specified for option {$argv[$i]}");
                }
                $options[$name] = $optPair[1];
            }
            elseif(in_array($name, $allowedOptions)) {
                $options[$name] = true;
            }
            else {
                throw new Exception("Unknown option {$argv[$i]}");
            }
        }
        else {
            if($subjectCount !== false && count($subjects) >= $subjectCount) {
                throw new Exception("Unknown option {$argv[$i]}");
            }
            $subjects[] = $argv[$i];
        }
    }
    if($subjectCount !== false && count($subjects) < $subjectCount) {
        throw new Exception("Some params are missing.");
    }
    return array($subjects, $options);
}
