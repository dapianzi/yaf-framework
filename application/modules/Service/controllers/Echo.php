<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-04-04 14:48
 * Created by PhpStorm.
 */
class EchoController extends CommonServiceController implements Interface_Service {

    public function iniAction() {
        $argv = $this->getRequest()->getParams();
        $v_cmd = array_shift($argv);
        switch ($v_cmd) {
            case 1001:
                $r = $this->sayworld($argv);
                break;
            default:
                $r = $this->sayhello($argv);
        }
        gf_ajax_success($r);
    }

    public function sayhello($argv) {
        return $argv;
    }

    public function sayworld($argv) {
        $color = 'rgby';
        while(TRUE) {
            gf_shell_echo(gf_shell_color('cmd 1001', $color[rand(0, 3)]));
            sleep(1);
        }
    }

}