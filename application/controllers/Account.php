<?php

/**
 *
 * @Author: Carl
 * @Since: 2018-03-30 18:17
 * Created by PhpStorm.
 */
class AccountController extends BaseController {

    public $auth = FALSE;

    public function loginAction() {
        // display template
        if (Yaf_Session::getInstance()->get('user') > 0) {
            $this->redirect('/');
        };
    }

    public function logoutAction() {
        Yaf_Session::getInstance()->del('user');
        $this->redirect('/');
    }

    public function onloginAction() {
        $username = $this->getPost('username', '');
        $password = $this->getPost('password', '');
        $captcha = $this->getPost('captcha_code', '');

        $session_obj = Yaf_Session::getInstance();
        if (strtoupper($session_obj->get('captcha_code_login')) !== strtoupper($captcha)) {
            gf_ajax_error('验证码错误'.$session_obj->get('captcha_code_login'));
        }
        $UserModel = new UserModel();
        $user = $UserModel->getUserByName($username);
        if ($user && $user['password'] == gf_encrypt_pwd($password, $user['salt'])) {
//            $token = $UserModel->login($user);
            Yaf_Session::getInstance()->set('user', $user['id']);
            gf_ajax_success($user);
        } else {
            gf_ajax_error('用户名或密码错误');
        }
    }

    public function passwordAction() {

    }

    public function setPasswordAction() {
        $old_pass = $this->getPost('old_pass', '');
        $new_pass = $this->getPost('new_pass', '');

        $userModel = new UserModel();
        $userinfo = $userModel->getUserInfo(Yaf_Session::getInstance()->get('user'));
        if (!$userinfo) {
            throw new SysException('404 NOT FOUND');
        }

        if ($new_pass == '') {
            gf_ajax_error('新密码不能为空!');
        }
        if ($userinfo['password'] != gf_encrypt_pwd($old_pass . $userinfo['salt'])) {
            gf_ajax_error('旧密码不匹配！');
        }
        // set new password
        $res = $userModel->set($userinfo['id'], 'password', gf_encrypt_pwd($new_pass . $userinfo['salt']));
        gf_ajax_success($res);
    }

    public function captchaAction() {
        $action = $this->getQuery('action', '');
        switch ($action) {
            case 'login':
            case 'forget': {

                break;
            }
            default: {
                throw new SysException('404 NOT FOUND');
            }
        }
        $captcha_code = gf_rand_str(4);
        Yaf_Session::getInstance()->set('captcha_code_'.$action, $captcha_code);

        $image = imagecreatetruecolor(130, 36);
        $fonttype = dirname(__FILE__) . '/../resources/'.rand(0, 4).'.ttf';

        // image bg color
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
        // draw captcha code.
        $len = strlen($captcha_code);
        for ($i=0; $i<$len; $i++) {
            $fontsize = rand(18, 28);        //
            $fontcolor = imagecolorallocate($image, rand(0, 160), rand(0, 160), rand(0, 160));//随机颜色
            $x = ($i*32) + rand(2, 10);   //随机坐标
            $y = rand($fontsize-2, 30);
            @imagettftext($image, $fontsize, rand(-30, 30), $x, $y, $fontcolor, $fonttype, $captcha_code[$i]);
        }
        // 增加干扰点
        for ($i=0; $i<300; $i++) {
            $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
            imagesetpixel($image, rand(1, 129), rand(1, 35), $pointcolor);//
        }

        // 增加干扰线
        for ($i=0; $i<5; $i++) {
            $linecolor = imagecolorallocate($image, rand(80, 280), rand(80,220), rand(80,220));
            imageline($image, rand(1, 129), rand(1, 35), rand(1, 129), rand(1, 35), $linecolor);
        }
        //输出格式
        header('content-type:image.png');
        imagepng($image);

        //销毁图片
        imagedestroy($image);
        exit;
    }

}