<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 22:07
 */

class LogoutController extends BaseController
{
    public function index() {
        unset($_SESSION['gantt_user']);
        $this->redirect($this->base_uri . '/login');
    }
}