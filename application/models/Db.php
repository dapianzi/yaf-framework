<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/11 15:54
 * Created by PhpStorm.
 */
class DbModel extends DbClass
{

    public function __construct($confName) {

        $conf = Yaf_Application::app()->getConfig()->$confName;
        parent::__construct($conf->dsn, $conf->username, $conf->password);
    }
}