<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 22:07
 */

class LogoutController extends BaseController
{
    public function indexAction() {
        unset($_SESSION);
        session_destroy();
        $this->redirect($this->base_uri . '/login');
        return false;
    }
}