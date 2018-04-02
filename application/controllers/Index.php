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
		// test pod class
		$menu = [];

		$menu = [
			[
				'page' =>'控制台',
				'href' => '/index/dashboard/',
				'icon' => 'home',
				'items' => [],
			],
			[
				'page' =>'系统设置',
				'icon' => 'set',
				'auto' => TRUE,
				'items' => [
					'参数设置' => '/system/index',
					'用户列表' => '/user/index/',
					'用户组' => '/user/group/',
					'权限管理' => '/user/permission-list/',
					'菜单管理' => '/menu/index/',
				]
			],
			[
				'page' =>'设备管理',
			 	'icon' => 'component',
			 	'auto' => FALSE,
			 	'items' => [
					'设备查询' => '/object/index/',
					'设备录入' => '/object/add/',
					'设备变更' => '/object/modify/',
				]
			],
			[
				'page'=>'报表统计',
				'icon' => 'analysis',
				'auto' => FALSE,
			 	'items' => [
					'设备统计' => '/analysis/objects/',
				]
			],
		];
		$this->assign('menu', $menu);
		$this->assign('unread_messages', 0);
	}

	public function dashboardAction() {
		$db = new DbModel();
		$env = [
			'php_version' => phpversion(),
			'mysql_version' => $db->getColumn("select version()")
		];
		$this->assign('env', $env);
	}



}
