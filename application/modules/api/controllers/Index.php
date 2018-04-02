<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/6 16:59
 * Created by PhpStorm.
 */
class IndexController extends BaseController {
    function indexAction() {
        Fn::ajaxSuccess(array(
            'controller' => __CLASS__,
            'action' => __METHOD__,
            'params' => $this->getRequest()->getParams(),
        ));
    }

    public function iniAction() {
        $argv = $this->getRequest()->getParams();
        $cmd = array_shift($argv);
        switch ($cmd) {
            case 1001:
                $r = $this->sayworld($argv);
                break;
            default:
                $r = $this->sayhello($argv);
        }
        Fn::ajaxSuccess($r);
    }

    public function sayhello($argv) {
        return $argv;
    }

    public function sayworld($argv) {
        while(TRUE) {
            echo 'cmd 1001';
            sleep(1);
        }
    }
}