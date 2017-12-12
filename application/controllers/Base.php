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
    protected $csrf_token = '';
    protected $is_ajax;
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

        // init request mode
        $this->is_ajax = $this->isAjax();

        // init csrf
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = substr(md5(uniqid() . strval(time())), 0, 32).substr(md5(uniqid() . strval(time())), 0, 32);;
        }
        $this->csrf_token = $_SESSION['csrf_token'];
        $this->getView()->assign('csrf_token', $this->csrf_token);
        $this->getView()->assign('csrf_input', $this->_csrf());

        // init user
        if ($this->auth) {
            if (isset($_SESSION['gantt_user'])) {
                $this->gantt_user  = (new UserModel())->getUserInfo($_SESSION['gantt_user']);
            }
            if (empty($this->gantt_user)) {
                if ($this->is_ajax) {
                    Fn::ajaxError('Invalid User. Please login first.');
                }
                $this->redirect($this->base_uri . '/login');exit;
            }
        }
        $this->getView()->assign('gantt_user', $this->gantt_user);
    }

    protected function _csrf() {
        return '<input type="hidden" name="csrf_token" value="'. $this->csrf_token .'" />';
    }

    protected function _valid_csrf() {
        $csrf_token = $this->getRequest()->getPost('csrf_token');
        if (isset($_SESSION['csrf_token']) && $csrf_token === $_SESSION['csrf_token']) {
            return TRUE;
        } else {
            throw new GanttException('Invalid request.');
        }
    }

    protected function getParam($key, $default='') {
        $s = $this->getRequest()->getParam($key, $default);
        if (empty($s)) {
            $s = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
        }
        return $this->safeInput($s);
    }

    protected function getQuery($key, $default='') {
        $s = isset($_GET[$key]) ? $_GET[$key] : $default;
        return $this->safeInput($s);
    }

    protected function getPost($key, $default='') {
        $s = isset($_POST[$key]) ? $_POST[$key] : $default;
        return $this->safeInput($s);
    }

    protected function safeInput($s) {
        if (is_array($s)) {
            foreach ($s as &$v) {
                $v = htmlspecialchars($v, ENT_QUOTES);
            }
        } else {
            $s = htmlspecialchars($s, ENT_QUOTES);
        }
        return $s;
    }

    protected function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return TRUE;
        }
        // todo: my ajax var
        if (!empty($this->getParam['MY_AJAX_VAR'])) {
            return TRUE;
        }
        return FALSE;
    }

    protected function _valid_user($user, $throwable=TRUE) {
        $valid = $this->gantt_user['is_admin'] || $this->gantt_user['username'] == $user;
        if ($throwable && !$valid) {
            throw new GanttException('Invalid User');
        }
        return $valid;
    }
}