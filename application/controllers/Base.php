<?php

/**
 * Created by PhpStorm.
 * User: KF
 * Date: 2017/3/24
 * Time: 15:26
 */
class BaseController extends Yaf_Controller_Abstract
{
    protected $base_uri;
    protected $dbLink;

    public function init() {
        $conf = Yaf_Application::app()->getConfig();
        $this->base_uri = $conf->application->baseUri;
        $this->dbLink = new DbClass($conf->mysql->dsn, $conf->mysql->username, $conf->mysql->password);
        if (!$this->dbLink->isConnectOk()) {
            exit($this->dbLink->getError());
        }

        $this->getView()->assign('BASE_URI', $this->base_uri);
        //$this->testDb();
    }

}