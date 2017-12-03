<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/6 16:59
 * Created by PhpStorm.
 */
class IndexController extends BaseController
{
    function indexAction() {
        echo 'dispatch ok.';
        $content = array(
            'uri' => $this->base_uri,
            'js' => $this->base_uri.'/public/static/js/yaf.admin/ajax.js'
        );
        $this->getView()->assign('content', $content);
        return true;

    }

    function customAction($aa) {
        echo __METHOD__.' -- custom --' .$aa;
        return false;
    }
}