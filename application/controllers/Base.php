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

    public $conf;
    public $is_ajax;
    public $user;
    public $auth = TRUE;

    public function init() {
        // inti config
        $conf = Yaf_Registry::get('config');
        $this->conf = $conf;
        $this->base_uri = $conf->application->baseUri;

        $this->getView()->assign('BASE_URI', $this->base_uri);
        // init request mode
        $this->is_ajax = $this->getRequest()->isXmlHttpRequest ();

        if ($this->auth) {

//            $UserModel = new UserModel();
//            $us$UserModel = new UserModel();
            $userinfo = [
                'id' => 1,
                'username' => 'offline',
                'nickName' => 'dapianzi',
            ];
            if (empty($userinfo)) {
                $this->redirect('/account/login/');
            }
//            if($userinfo['status']!=1){
//                throw new SysException('The account is not allowed to account. Please contact administrator！');
//            }
//            $UserRoleStatus=$UserModel->getUserRoleStatus($userinfo);
//            if($UserRoleStatus!=1){
//                throw new SysException('The role is not allowed to account. Please contact administrator！');
//            }
            $this->user = $userinfo;
        }

        //判断用户在当前节点是否有权限
//        $AuthModel=new AuthModel();
//        $node=strtolower('/'.$this->getRequest()->module.'/'.$this->getRequest()->controller.'/'.$this->getRequest()->action.'/');
//        $AUTH=$AuthModel->getCurrentAuth($userinfo,$node);
//        if(!$AUTH){
//            throw new SysException('No authority. Please contract administrator！');
//        }
//        //记录访问记录及操作
//        $this->addUserActionLog($this->user, $node, $_SERVER['REQUEST_URI']);
        $this->getView()->assign('user', $this->user);
//        $this->getView()->assign('nodeName', $AuthModel->getNodeName($node));
//        $this->getView()->assign('parentNode', $AuthModel->getParentNode($node));
    }

    /**
     * 记录访问记录及操作
     * @param $user 用户
     * @param $node 节点
     * @param $uri 地址
     */
    function addUserActionLog($user,$node,$uri){
        $ip=gf_get_remote_addr();
        $UserModel=new UserModel();
        $UserModel->addUserActionLog($user,$node,$uri,$ip);
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