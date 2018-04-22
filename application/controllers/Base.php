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
        // init request mode
        $this->is_ajax = $this->getRequest()->isXmlHttpRequest ();

        // init user
        if ($this->auth) {
            $UserModel = new UserModel();
            $userinfo = $UserModel->getUserInfo(Yaf_Session::getInstance()->get('user'));
            if (empty($userinfo)) {
                Yaf_Session::getInstance()->del('user');
                $this->redirect('/admin/account/login/');
            }
            if (STATUS_NO_USE == $userinfo['status']) {
                throw new SysException('Access denied! Your account has been blocked.');
            }
            if (STATUS_NO_USE == $userinfo['role_status']) {
                throw new SysException('Access denied! The role of your account has been blocked.');
            }
            $this->user = $userinfo;
            $curr_node = strtolower('/'.$this->getModuleName().'/'.$this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName().'/');
            $MenuModel = new MenuModel();
            $this->assign('node_nav', $MenuModel->getNodeName($curr_node));
            //判断用户在当前节点是否有权限
            if ($this->user['role_id'] != ROLE_SUPERADMIN) {
                $node_id = $MenuModel->getMenuId($curr_node);
                $valid = (new RoleModel())->validPermission($node_id, $this->user['role_id']);
                if (!$valid) {
                    throw new SysException('Access denied. Your account don not have the permission to visit this url.');
                }
            }

            //记录访问记录及操作
            $this->log('系统访问日志');
        }
        $this->assign('user', $this->user);
    }

    /**
     * @param $action [ACTION_ADD, ACTION_UPDATE, ACTION_DEL, ACTION_VIEW]
     * @param $result [RESULT_SUCCESS, RESULT_FAIL]
     * @param $details
     * @return bool|string
     */
    public function log($details, $action=ACTION_VIEW, $result=RESULT_SUCCESS){
        $ip = gf_get_remote_addr();
        $uri = $_SERVER['REQUEST_URI'];
        $data = json_encode($_POST, JSON_UNESCAPED_SLASHES);
        $uid = $this->user['id'];
        $details = '['.gf_now().'] ' . $details;

        $data = array(
            'action' => $action,
            'result' => $result,
            'uid'=> $uid,
            'ip' => $ip,
            'uri' => $uri,
            'data'=> $data,
            'details' => $details,
        );
        return (new UserModel())->addUserLogs($data);
    }

    /**
     * 重写render方法，自动根据模块加载模板
     */
    protected function render($action, array $parameters = null){
        $template_name = str_ireplace('Controller', '', get_class($this)).'/'.$action;
        $module = strtolower($this->getRequest()->module);
        $template_name = $module.'/'.$template_name;
//        if ($module != $this->conf->application->dispatcher->defaultModule) {
//        }
        $template_name = strtolower($template_name.'.'.$this->conf->application->view->ext);
        return $this->getView()->render($template_name, $parameters);
    }

    protected function getParam($key, $args=null) {
        $s = $this->getRequest()->getParam($key);
        if (is_null($s)) {
            $s = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        }
        return $this->_validInputs($key, $s, $args);
    }

    protected function getQuery($key, $args='') {
        $s = isset($_GET[$key]) ? $_GET[$key] : null;
        return $this->_validInputs($key, $s, $args);
    }

    protected function getPost($key, $args='') {
        $s = isset($_POST[$key]) ? $_POST[$key] : null;
        return $this->_validInputs($key, $s, $args);
    }

    public function assign($key, $var) {
        $this->getView()->assign($key, $var);
    }

    public function _validInputs($key, $input, $args) {
        if (!is_array($args) || empty($args)) {
            return is_null($input) ? $args : $input;
        }
        // default
        $args = array_merge([
            'filter' => FALSE,
        ], $args);

        if (isset($args['null']) && is_null($input)) {
            throw new SysException(sprintf('[%s] can not be null.', $args['null']));
        }
        if (isset($args['empty']) && empty($input)) {
            throw new SysException(sprintf('[%s] can not be empty.', $args['empty']));
        }
        if (isset($args['in']) && !in_array($input, explode(',', $args['in']))) {
            throw new SysException('Invalid Params.');
        }
        if (isset($args['not']) && in_array($input, explode(',', $args['not']))) {
            throw new SysException('Invalid Params.');
        }
        if (isset($args['expr']) && !preg_match($args['expr'], $input)) {
            throw new SysException('Invalid Params.');
        }
        if (isset($args['type'])) {
            switch (strtolower($args['type'])) {
                case 'int':
                    $input = is_array($input) ? array_map('intval', $input) : intval($input);
                    break;
                case 'array':
                    $input = is_array($input) ? $input : [$input];
                    break;
                default:
            }
        }
        if (isset($args['default']) && is_null($input)) {
            $input = $args['default'];
        }
        if (isset($args['filter']) && $args['filter']) {
            $input = gf_safe_input($input);
        }
        return $input;
    }

}