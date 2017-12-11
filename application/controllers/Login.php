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
                $_SESSION['gantt_user'] = $username;
                $ref = $req->getPost('ref');
                $ref = empty($ref) ? $this->base_uri : urldecode($ref);
                $this->redirect($ref);exit;
            } else {
                $err = 'Username or password is incorrect.';
                $this->getView()->assign('err', $err);
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
            $nickname = $this->getPost('nickname', $username);
            $invitation = $this->getPost('invitation_code', '');
            // invitation code
            if ($invitation != 'huihuige') {
                $invitation_code = (new DbClass('mysql:host=47.89.251.85;dbname=ip_test;port=3307', 'ip', 'ip'))->getColumn("SELECT invitation_code FROM invitation_code WHERE id=1");

                if (strtolower($invitation) != htmlspecialchars(strtolower($invitation_code), ENT_QUOTES)) {
                    Fn::ajaxError('邀请码都没有，走好，不送！');
                }
            }
            // valid username
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
            $res = $userModel->add($data);
            if ($res) {
                Fn::ajaxSuccess($this->base_uri . '/login');
            } else {
                Fn::ajaxError('未知错误：' . $userModel->getError());
            }
        }
        $this->getView()->assign('title', '账号注册');
    }


    public function invitation_codeAction() {
        // 直接获取 $_SERVER ，留下注入漏洞
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = 'unknow';
        }
        echo 'Your IP address have been recorded: '.$cip;
        try {

            $DB = new DbClass('mysql:host=47.89.251.85;dbname=ip_test;port=3307', 'ip', 'ip');
            $sql = "INSERT INTO IP (ip,sid) VALUES ('$cip', '".session_id()."') ";
            $DB->query($sql);
        } catch (Yaf_Exception $e) {

        }
        exit;
    }
}