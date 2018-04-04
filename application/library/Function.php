<?php
/**
 * Created by PhpStorm.
 * User: KF
 * Date: 2017/3/24
 * Time: 15:31
 */

/** 方便git merge carl **/
function gf_dump($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function gf_safe_input($s) {
    if (empty($s)) {
        return $s;
    } else {
        return is_array($s) ? array_map('gf_safe_input', $s) : addslashes($s);
    }

}

function gf_ajax_error($msg='') {
    return gf_ajax_return([], -1, $msg);
}

function gf_ajax_success($data, $extra=[]) {
    return gf_ajax_return($data, 0, '', $extra);
}

function gf_ajax_return($data, $code, $msg, $extra=[]) {
//    header('Content-Type:application/json; charset=utf-8');
    return exit(json_encode(array_merge(array(
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ), $extra), JSON_UNESCAPED_UNICODE));
}

/**
 * 安全的获取客户端IP
 * @return string
 */
function gf_get_remote_addr() {
    if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if(!empty($_SERVER["REMOTE_ADDR"])) {
        $cip = $_SERVER["REMOTE_ADDR"];
    } else {
        $cip = '';
    }
    // ipv4 & ipv6
    preg_match("/[\d\.]{7,15}|[:0-9a-fA-F]+/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);
    return $cip;
}

/**
 *  $data = [
 *      'op' => 'xxxx',
 *      'ap' => 'ahrega',
 *  ];
 *  $template = "风格{{op}}日哈尔{{ap}}啊然{{op}}后";
 *
 *  return 风格xxxx日哈尔ahrega啊然xxxx后
 *
 * @param $template
 * @param $data
 * @return string
 */
function gf_render_template($template, $data) {
    if (preg_match_all('/\{\{(.*?)\}\}/', $template, $matches)) {
        foreach ($matches[1] as $m) {
            $template = str_replace('{{'.$m.'}}', $data[$m], $template);
        }
    }
    return $template;
}


/** 方便git merge danny **/


/** 方便git merge sky **/

