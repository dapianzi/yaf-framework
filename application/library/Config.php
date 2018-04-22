<?php
/**
 * Created by PhpStorm.
 * User: Carl
 * Date: 2018/4/17 11:23
 */

define('LOG_FILE'               , 'sys-error.log');

// define exception code
define('SYS_EXCEPTION'          , 10000);
define('DB_EXCEPTION'           , 10001);
define('PARAMS_EXCEPTION'       , 10002);

// define status
define('STATUS_OK'              , 0);
define('STATUS_NO_USE'          , -1);

// define exception code
define('ROLE_SUPERADMIN'        , 1);

// log action
define('ACTION_VIEW'            , 0);
define('ACTION_ADD'             , 1);
define('ACTION_UPDATE'          , 2);
define('ACTION_DELETE'          , 3);

// result code
define('RESULT_SUCCESS'         , 0);
define('RESULT_FAIL'            , -1);

// permit
define('PERM_DEFAULT'           , '{"access": [1,3,4,5]}');