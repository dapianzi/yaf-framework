<?php
/**
 * Created by PhpStorm.
 * @Author: Carl
 * @Since: 2017/3/24  15:31
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
        // 统一在PDO中使用参数绑定，避免双层转义
        return $s;
        //return is_array($s) ? array_map('gf_safe_input', $s) : addslashes($s);
    }
}

function gf_now($time = 0) {
    $time = empty($time) ? time() : $time;
    return date('Y-m-d H:i:s', $time);
}

function gf_shell_echo($var) {
    echo '[' . gf_now().'] ';
    if (is_array($var) || is_object($var)) {
        print_r($var);echo "\n";
    } else {
        echo $var."\n";
    }
}

/**
 * @param string $str
 * @param string $color
 * @return string
 */
function gf_shell_color($str, $color='') {
//    QUOTE:
//    字背景颜色范围: 40--49                   字颜色: 30--39
//                40: 黑                           30: 黑
//                41: 红                           31: 红
//                42: 绿                           32: 绿
//                43: 黄                           33: 黄
//                44: 蓝                           34: 蓝
//                45: 紫                           35: 紫
//                46: 深绿                         36: 深绿
//                47: 白色                         37: 白色
    switch ($color) {
        case 'r':
        case 'red':
            $color = 31;
            break;
        case 'g':
        case 'green':
            $color = 32;
            break;
        case 'b':
        case 'blue':
            $color = 34;
            break;
        case 'y':
        case 'yellow':
            $color = 33;
            break;
        default:
            $color = 37;
    }

    return sprintf("\033[40;%dm%s\033[0m", $color, $str);
}

function gf_ajax_error($msg='') {
    return gf_ajax_return([], -1, $msg);
}

function gf_ajax_success($data, $extra=[]) {
    return gf_ajax_return($data, 0, '', $extra);
}

function gf_ajax_return($data, $code, $msg, $extra=[]) {
    header('Content-Type:application/json; charset=utf-8');
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
        $chp = $_SERVER["HTTP_CLIENT_IP"];
    } else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $chp = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if(!empty($_SERVER["REMOTE_ADDR"])) {
        $chp = $_SERVER["REMOTE_ADDR"];
    } else {
        $chp = '';
    }
    // ipv4 & ipv6
    preg_match("/[\d\.]{7,15}|[:0-9a-fA-F]+/", $chp, $chps);
    $chp = isset($chps[0]) ? $chps[0] : 'unknown';
    unset($chps);
    return $chp;
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
            if (!isset($data[$m])) {
                throw new SysException('模板变量{{'.$m.'}}缺失');
            }
            $template = str_replace('{{'.$m.'}}', $data[$m], $template);
        }
    }
    return $template;
}

/**
 * http post 请求
 * @param  [string] $url        [description]
 * @param  [string] $parameters [description]
 * @param  array  $headers    [description]
 * @return [obj]             [description]
 */
function gf_http_post($url,$parameters = NULL, $headers = array()){
    return gf_http($url,'post' ,$parameters , $headers );
}

/**
 * http get 请求
 * @param  [string] $url        [description]
 * @param  [string] $parameters [description]
 * @param  array  $headers    [description]
 * @return [obj]             [description]
 */
function gf_http_get($url, $parameters = NULL,$headers = array()){
    return gf_http($url,'get' ,$parameters , $headers );
}

/**
 * [gf_http description]
 * @param  [type] $url        [description]
 * @param  [type] $method     [description]
 * @param  [type] $parameters [description]
 * @param  array  $headers    [description]
 * @return [type]             [description]
 */
function gf_http($url, $method, $parameters = NULL, $headers = array()) {
    if(empty($url)){return NULL;}
    $ch = curl_init();
    /* Curl settings */
    curl_setopt($ch, CURLOPT_HTTP_VERSION           , CURL_HTTP_VERSION_1_0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER         , TRUE);
    curl_setopt($ch, CURLOPT_TIMEOUT                , 3000);
    curl_setopt($ch, CURLOPT_HEADER                 , FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER         , FALSE); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST         , 2);  // 从证书中检查SSL加密算法是否存在
    switch (strtolower($method)) {
        case 'post':
            curl_setopt($ch, CURLOPT_POST, TRUE);
            if (!empty($parameters)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            }
            break;
        case 'get':
            if (!empty($parameters)) {
                $url .= strpos($url, '?') === false ? '?' : '&';
                $url .= http_build_query($parameters);
            }
            break;
        default:
            # code...
            break;
    }
    curl_setopt($ch, CURLOPT_URL                    , $url );
    curl_setopt($ch, CURLOPT_HTTPHEADER             , $headers);
    curl_setopt($ch, CURLINFO_HEADER_OUT            , TRUE );
    $response = curl_exec($ch);
    curl_close ($ch);
    return $response;
}

function gf_rand_str($n) {
    if (!is_int($n)) {
        throw new Exception('argument must be int');
    }
    $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i=0; $i<$n; $i++) {
        $str .= $alpha[rand(0, 35)];
    }
    return $str;
}
