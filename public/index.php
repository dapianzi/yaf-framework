<?php
define('APP_PATH', dirname(__FILE__) . '/../');
define("YAF", 1);
define('STARTTIME', microtime(true));
define('WS_EXCEPTION', 10000);

$application = new Yaf_Application( APP_PATH . "/conf/application.ini");
$application->bootstrap()->run();
echo '<!--' . round((microtime(true) - STARTTIME) * 1000) . 'ms-->';
