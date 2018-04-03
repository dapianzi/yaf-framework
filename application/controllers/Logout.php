<?php

class LogoutController extends Yaf_Controller_Abstract {
    public function indexAction() {
        phpCAS::logoutWithRedirectService('http://' . $_SERVER['SERVER_NAME']);
    }
}