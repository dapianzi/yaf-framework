<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-03-30 18:17
 * Created by PhpStorm.
 */
class LoginController extends BaseController {

    protected $auth = FALSE;

    public function indexAction() {

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
}