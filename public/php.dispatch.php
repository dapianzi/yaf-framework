<?php
/**
 *
 * @Author: Carl
 * @Since: 2017/4/7 17:51
 * Created by PhpStorm.
 */

is_cli() || die('Bad Request');
array_shift($argv);
$args = init_argvs($argv);

define('APPLICATION_PATH', dirname(__FILE__));
$application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");

$application->getDispatcher()->dispatch(new Yaf_Request_Simple('CLI', 'Yaf.cli', $args['c'], $args['a'], $args['p']));

function is_cli(){
    return preg_match("/cli/i", php_sapi_name()) ? TRUE : FALSE;
}

function init_argvs($args) {
    $ret = array();
    $ret['c'] = isset($args[0]) ? $args[0] : 'Index';
    @array_shift($args);
    $ret['a'] = isset($args[0]) ? $args[0] : 'Index';
    @array_shift($args);
    $ret['p'] = $args;
    return $ret;
}
