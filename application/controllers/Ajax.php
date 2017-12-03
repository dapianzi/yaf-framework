<?php

/**
 * Created by PhpStorm.
 * User: KF
 * Date: 2017/3/24
 * Time: 16:04
 */
class AjaxController extends Yaf_Controller_Abstract
{
    public function indexAction() {
        $t = $_REQUEST['t'];
        $res = array(
            'content' => date('Y-m-d H:i:s', $t+0) . ' : ' . ' response ok.'
        );
        echo json_encode($res);exit;
        return false;
    }
}