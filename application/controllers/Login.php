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
        $this->getView()->assign('csrf', $this->_csrf());
    }


    public function signAction(){
        $req = $this->getRequest();

        if ($req->getPost('action') === 'sign') {
            $this->_valid_csrf();

            $userModel = new UserModel();
            $username = $req->getPost('username', '');
            $password = $req->getPost('password', '');
            $email = $req->getPost('email', '');
            $nickname = $req->getPost('nickname', '');
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
            $userid = $userModel->insert($userModel->table, $data);
            Fn::ajaxSuccess($userid);
        }
        $this->getView()->assign('title', '账号注册');
        $this->getView()->assign('csrf', $this->_csrf());
    }

}