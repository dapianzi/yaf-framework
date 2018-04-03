<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author KF
 */
class ErrorController extends Yaf_Controller_Abstract {

	//从2.1开始, errorAction支持直接通过参数获取异常
//	public function errorAction($exception) {
//		//1. assign to view engine
//		$this->getView()->assign("exception", $exception);
//		//5. render by Yaf
//	}

	public function errorAction($exception) {
		if ($this->getRequest()->isXmlHttpRequest()) {
			gf_ajax_error($exception->getMessage());
		}
		//functionClass::dump(Yaf_Application::$modules);
		switch($exception->getCode()) {

//            case WS_INVALID_ADMIN:
			case WS_EXCEPTION:{
				header("HTTP/1.1 404 Not Found");
				break;
			}
//            case YAF_ERR_LOADFAILD:
//            case YAF_ERR_LOADFAILD_MODULE:
//            case YAF_ERR_LOADFAILD_CONTROLLER:
//            case YAF_ERR_LOADFAILD_ACTION:
			default:
			{
				//404
                //header("HTTP/1.1 503 Server Error.");
				header("HTTP/1.1 404 Not Found");
				break;
			}
		}

		$this->getView()->assign('code', $exception->getCode());
		$this->getView()->assign('message', $exception->getMessage());
		$this->getView()->assign('exception', get_class($exception));
		$this->getView()->display('public/error.html');
		return FALSE;
	}
}
