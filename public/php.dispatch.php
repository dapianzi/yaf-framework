<?php
/**
 *
 * @Author: Carl
 * @Since: 2017/4/7 17:51
 * Created by PhpStorm.
 */
define('APP_PATH', dirname(__FILE__) . "/../");
$application = new Yaf_Application(APP_PATH . "conf/application.ini");

function dispatch(&$args) {
    // script name
    array_shift($args);
    $route = array(
        1000 => 'echo',
    );
    $v_class = array_shift($args);
    if (isset($route[$v_class])) {
        return $route[$v_class];
    } else {
        die('Invalid Request');
    }
}

$application->bootstrap()->getDispatcher()->dispatch(new Yaf_Request_Simple('CLI', 'service', dispatch($argv), 'ini', $argv));

