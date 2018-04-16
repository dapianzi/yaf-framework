<?php
/**
 *
 * @Author: carl
 * @Since: 2018/4/13 ${time}
 * Created by PhpStorm.
 */

class CommonapiController extends Yaf_Controller_Abstract
{

    protected $auth = TRUE;
    protected $user = [];
    protected $params = [];

    public function init() {
        if ($this->auth) {
            $this->_validUser();
        }
        $params = isset($_REQUEST['params']) ? $_REQUEST['params'] : '';
        $this->params = json_decode($params, TRUE);
    }

    public function _validUser() {
        $token = $this->getParam('auth_token', '');
        $this->user = [
            'username' => 'carl',
            'id' => 1,
        ];
        return TRUE;
    }

    public function getParams() {
        return $this->params;
    }

    public function getParam($name, $default='') {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

}