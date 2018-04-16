<?php
/**
 * @name IndexController
 * @author Carl
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/sample/index/index/index/name/KF 的时候, 你就会发现不同
     */
	public function indexAction() {
//        $menuModel = new MenuModel();
//        $menuList = $menuModel->getMenuList($this->user);
        $menuList = [
            'index' => [
                'page' => '控制台',
                'href' => '/index/index/dashboard/',
                'icon' => 'home'
            ],
            'system' => [
                'page' => '系统配置',
                'link' => '',
                'icon' => 'config',
                'items' => [
                    '系统用户管理' => '/system/user/',
                    '用户权限' => '/system/permission/',
                    '系统日志' => '/system/logs/',
                    '系统参数配置' => '/system/config/',
                ]
            ]
        ];
		$this->assign('menu', $menuList);
		// extra info
		$this->assign('unread_messages', 12);
	}

	public function dashboardAction() {
//		$db = new DbModel();
		$env = [
			'php_version' => phpversion(),
			'mysql_version' => 'none',//$db->getColumn("SELECT VERSION();"),
			'yaf_version' => YAF_VERSION,
			'yaf_env' => Yaf_Application::app()->environ(),
		];
		$this->assign('env', $env);
	}



}
