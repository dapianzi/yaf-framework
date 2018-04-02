<?php
/**
 *
 * @Author: Carl
 * @Since: 2017/4/7 17:51
 * Created by PhpStorm.
 */


function is_cli(){
    return preg_match("/cli/i", php_sapi_name()) ? TRUE : FALSE;
}
is_cli() || die('Bad Request');

define('APPLICATION_PATH', dirname(__FILE__) . "/../");
$application = new Yaf_Application(APPLICATION_PATH . "conf/application.ini");

function dispatch(&$args) {
    // script name
    array_shift($args);
    if (count($args) < 2) {
        die('Invalid Request');
    }
    $route = array(
        1000 => 'index',
    );
    $c = array_shift($args);
    if (isset($route[$c])) {
        return $route[$c];
    } else {
        die('Invalid Request');
    }
}

$application->getDispatcher()->dispatch(new Yaf_Request_Simple('CLI', 'api', dispatch($argv), 'ini', $argv));

