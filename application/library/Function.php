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
        // 统一在PDO中使用参数绑定，避免双层转义
        return $s;
        //return is_array($s) ? array_map('gf_safe_input', $s) : addslashes($s);
    }
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
            if (!isset($data[$m])) {
                throw new WSException('模板变量{{'.$m.'}}缺失');
            }
            $template = str_replace('{{'.$m.'}}', $data[$m], $template);
        }
    }
    return $template;
}


/** 方便git merge danny **/
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
function gf_http($url,$method,$parameters = NULL, $headers = array()) {
    if(empty($url)){return NULL;}
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_TIMEOUT, 3000);
    curl_setopt($ci, CURLOPT_HEADER, FALSE);
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    switch (strtolower($method)) {
        case 'post':
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if (!empty($parameters)) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $parameters);
            }
            break;
        case 'get':
         if (!empty($parameters)) {
            $url .= strpos($url, '?') === false ? '?' : '';
            $url .= http_build_query($parameters);
        }
            break;
        default:
            # code...
            break;
    }
    curl_setopt($ci, CURLOPT_URL, $url );
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
    $response = curl_exec($ci);
    curl_close ($ci);
    return $response;
}

/** 方便git merge sky **/

