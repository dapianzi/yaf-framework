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
    }

    function customAction($aa) {
        echo __METHOD__.' -- custom --' .$aa;
    }
}