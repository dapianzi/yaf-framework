<?php
//phpinfo();exit;
define('APP_PATH', dirname(__FILE__) . '/../');

define('STARTTIME', microtime(true));
define('SYS_EXCEPTION', 10000);
//include_once APP_PATH . '/CasClient.php';

$application = new Yaf_Application( APP_PATH . "/conf/application.ini");
$application->bootstrap()->run();
echo '<!--' . round((microtime(true) - STARTTIME) * 1000) . 'ms-->';
