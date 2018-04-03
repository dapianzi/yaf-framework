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
    protected $auth = FALSE;

    public $conf;
    public $is_ajax;
    public $user;

    public function init() {
        // inti config
        $conf = Yaf_Registry::get('config');
        $this->conf = $conf;
        $this->base_uri = $conf->application->baseUri;

        $this->getView()->assign('BASE_URI', $this->base_uri);
        // init request mode
        $this->is_ajax = $this->getRequest()->isXmlHttpRequest ();
        if (!phpCAS::isAuthenticated()) {
            phpCAS::forceAuthentication();
        } else {
            $UserModel=new UserModel();
            if(phpCAS::hasAttribute('username')){
                $userinfo = $UserModel->getUserInfo(phpCAS::getAttributes());
            }
            if (empty($this->user)) {
                if ($this->is_ajax) {
                    Fn::ajaxError('Invalid User. Please login first.');
                }
            }
            $this->user = $userinfo;
        }


        $this->getView()->assign('user', $this->user);
    }

    /**
     * 重写render方法，自动根据模块加载模板
     */
    protected function render($action, array $parameters = null){
        $template_name = str_ireplace('Controller', '', get_class($this)).'/'.$action;
        $module = strtolower($this->getRequest()->module);
        if ($module != $this->conf->application->dispatcher->defaultModule) {
            $template_name = $module.'/'.$template_name;
        }
        $template_name = strtolower($template_name.'.'.$this->conf->application->view->ext);
        return $this->getView()->render($template_name, $parameters);
    }

    protected function getParam($key, $default='') {
        $s = $this->getRequest()->getParam($key, $default);
        if (empty($s)) {
            $s = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
        }
        return gf_safe_input($s);
    }

    protected function getQuery($key, $default='') {
        $s = isset($_GET[$key]) ? $_GET[$key] : $default;
        return gf_safe_input($s);
    }

    protected function getPost($key, $default='') {
        $s = isset($_POST[$key]) ? $_POST[$key] : $default;
        return gf_safe_input($s);
    }

    public function assign($key, $var) {
        $this->getView()->assign($key, $var);
    }

}