<?php
/**
 *
 * @Author: carl
 * @Since: 2018/4/13 ${time}
 * Created by PhpStorm.
 */

class CommonServiceController extends Yaf_Controller_Abstract
{

    public function init() {

        if (strtolower($this->getRequest()->getMethod()) !== 'cli') {
            header("HTTP/1.1 502 Bad Request.");
            exit('Bad Request');
        }
        gf_shell_echo('controller init.');
    }

}