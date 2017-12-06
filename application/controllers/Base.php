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
    protected $gantt_user;
    protected $csrf_token;
    protected $auth = TRUE;

    public function init() {
        // inti config
        $conf = Yaf_Application::app()->getConfig();
        $this->base_uri = $conf->application->baseUri;
        $this->dbLink = new DbClass($conf->mysql->dsn, $conf->mysql->username, $conf->mysql->password);
        if (!$this->dbLink->isConnectOk()) {
            exit($this->dbLink->getError());
        }
        $this->getView()->assign('BASE_URI', $this->base_uri);

        // init session
        if (!session_id()) {
            session_start();
        }

        // init csrf;
        $this->csrf_token = substr(md5(uniqid() . strval(time())), 0, 16);
        if (isset($_SESSION['csrf_token'])) {
            $_SESSION['pref_csrf_token'] = $_SESSION['csrf_token'];
        }
        $_SESSION['csrf_token'] = $this->csrf_token;
        $this->getView()->assign('csrf_token', $this->csrf_token);
        $this->getView()->assign('csrf_input', $this->_csrf());

        // init user
        if ($this->auth) {
            if (isset($_SESSION['gantt_user'])) {
                $this->gantt_user  = (new UserModel())->getUserInfo($_SESSION['gantt_user']);
            }
            if (empty($this->gantt_user)) {
                $this->redirect($this->base_uri . '/login');exit;
            }
        }
    }

    protected function _csrf() {
        return '<input type="hidden" name="csrf_token" value="'. $this->csrf_token .'" />';
    }

    protected function _valid_csrf() {
        $csrf_token = $this->getRequest()->getPost('csrf_token');
        if (isset($_SESSION['pref_csrf_token']) && $csrf_token === $_SESSION['pref_csrf_token']) {
            return TRUE;
        } else {
            throw new GanttException('Invalid request.');
        }
    }

}