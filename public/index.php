<?php
define('APP_PATH', dirname(__FILE__) . '/../');
define("YAF", 1);
define('STARTTIME', microtime(true));
define('WS_EXCEPTION', 10000);

define('SYS_CAS_CLIENT',        'cas-client');
define('SYS_DISCUZ',            'discuz');
define('SYS_CACTI',             'cacti');
define('SYS_RACKTABLES',        'racktables');
define('SYS_OTRS',              'otrs');
define('SYS_ZABBIX',            'zabbix');
define('SYS_CMDB',            'ws-cmdb');

include_once APP_PATH . '/CasClient.php';

$application = new Yaf_Application( APP_PATH . "/conf/application.ini");
$application->bootstrap()->run();
//echo '<!--' . round((microtime(true) - STARTTIME) * 1000) . 'ms-->';
