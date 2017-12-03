<?php
/**
 * @name IndexController
 * @author KF
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

		echo 'something wrong';exit;

		$progress = array(
			array('name'=>'Perl',	'progress' => 50*(200/200) + 50*(100/600)),
			array('name'=>'Yaf',	'progress' => 80),
			array('name'=>'Laravel','progress' => 8	),
			array('name'=>'Yii2',	'progress' => 0	),
			array('name'=>'Python',	'progress' => 15),
			array('name'=>'概率论',	'progress' => 20*100/200),
			array('name'=>'驾校科目1',	'progress' => 880*100/1350),
		);

		$this->getView()->assign("progress", $progress);

        return TRUE;
	}

	public function weChatAction() {

		//TODO get history chat message.

		return TRUE;
	}

	public function customAction($aa = '') {
		$cc = $this->_request->getParam('cc');
		echo 'Catch arguments $cc: '. $cc .'<br />';

		echo '<pre>';
		$sql = "SHOW TABLES";
		var_dump($this->dbLink->getAll($sql));
		echo '</pre>';
		if ((new SampleModel())->insertSample($aa)) {
			$this->getView()->assign('content', 'done.');
			return true;
		} else {
			echo 'no render';
			return false;
		}
	}
}
