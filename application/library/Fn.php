<?php
/**
 * Created by PhpStorm.
 * User: KF
 * Date: 2017/3/24
 * Time: 15:31
 */


class Fn {

    public static function dump($var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    public static function ajax_error($err='') {
        return self::ajaxReturn(-1, $err);
    }

    public static function ajax_success($data='') {
        return self::ajaxReturn(0, $data);
    }

    public static function ajaxReturn($status, $content) {
        header('Content-Type:application/json; charset=utf-8');
        return exit(json_encode(array(
            'status' => $status,
            'content' => $content
        )));
    }
}
