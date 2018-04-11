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

define('APP_PATH', dirname(__FILE__) . "/../");
$application = new Yaf_Application(APP_PATH . "conf/application.ini");

function dispatch(&$args) {
    // script name
    array_shift($args);
    $route = array(
        1000 => 'Otrs',
    );
    if (isset($route[$args[0]])) {
        return $route[$args[0]];
    } else {
        die('Invalid Request');
    }
}

$application->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple('CLI', 'api', dispatch($argv), '', $argv));

