<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-03-30 18:17
 * Created by PhpStorm.
 */
class AccountController extends BaseController {

    public $auth = FALSE;

    public function loginAction() {
        // display template
    }

    public function logoutAction() {
        Yaf_Session::getInstance()->del('user');
        $this->redirect('/');
    }
    public function doLoginAction() {
        $user = $this->getPost('name');
        $_SESSION['user'] = $user;
//        if ((new UserModel())->getUserInfo($user)) {
//            $_SESSION['user'] = $user;
//            $this->redirect('');
//        } else {
//            $this->forward('index');
//        }
        return FALSE;
    }

    public function forgetAction() {

    }

    public function captchaAction() {
        $action = $this->getQuery('action', '');
        switch ($action) {
            case 'login':
            case 'forget': {

                break;
            }
            default: {
                throw new SysException('404 NOT FOUND');
            }
        }
        $random_str = gf_rand_str(5);
        $_SESSION['captcha_code_'.$action] = $random_str;
        header('application/png');
        $im = imagecreatetruecolor(250, 50);


        exit;
    }

}