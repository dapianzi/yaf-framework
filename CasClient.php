<?php
include_once (dirname ( __FILE__ ) . '/CAS/CAS.php'); // 注意这个

//define ('CAS_SERVER_HOST', '172.22.12.128');
define ('CAS_SERVER_HOST', 'cas.765.com.cn');
define ('CAS_SERVER_PORT', 8080);
//define ('CAS_SERVER_HOST', '183.2.213.64');
//define ('CAS_SERVER_PORT', 8088);
define ('CAS_SERVER_PATH', 'cas-server');
// 初始化
phpCAS::setDebug (FALSE);

// initialize phpCAS
phpCAS::client ( CAS_VERSION_2_0, CAS_SERVER_HOST, CAS_SERVER_PORT, CAS_SERVER_PATH );

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation ();

phpCAS::handleLogoutRequests(FALSE);

