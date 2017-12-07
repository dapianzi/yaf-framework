<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/3 0003
 * Time: 12:59
 */

class LoginController extends BaseController
{
    protected $auth = FALSE;

    public function indexAction() {
        $req = $this->getRequest();

        if ($req->getPost('action') === 'login') {
            $this->_valid_csrf();
            $username = $req->getPost('username');
            $password = $req->getPost('password');
            $userInfo = (new UserModel())->getUserInfo($username);
            if ($userInfo && $userInfo['password'] === sha1($password)) {
                $ref = $req->getPost('ref');
                $ref = empty($ref) ? $this->base_uri : urldecode($ref);
                $this->redirect($ref);exit;
            } else {
                $err = 'Username or password is incorrect.';
            }
        }
        $this->getView()->assign('title', '账号登录');
        $this->getView()->assign('ref', urlencode($req->getQuery('request', '')));
    }


    public function signAction(){

        if ($this->getPost('action') === 'sign') {
            $this->_valid_csrf();

            $userModel = new UserModel();
            $username = $this->getPost('username', '');
            $password = $this->getPost('password', '');
            $email = $this->getPost('email', '');
            $nickname = $this->getPost('nickname', '');
            // valid
            if ($userModel->getUserInfo($username)) {
                Fn::ajaxError('Username: '. $username .' is already existed.');
            }
            // todo: validate email, password

            $data = array(
                'username' => $username,
                'password' => sha1($password),
                'email' => $email,
                'nickname' => $nickname,
            );
            $res = $userModel->insert($userModel->table, $data);
            if ($res) {
                Fn::ajaxSuccess($this->base_uri . '/login');
            } else {
                Fn::ajaxError('未知错误：' . $userModel->getError());
            }
        }
        $this->getView()->assign('title', '账号注册');
    }

}