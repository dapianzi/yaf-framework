<?php
/**
 * Created by PhpStorm.
 * User: sky / ouyangdongming
 * Date: 2018/4/8
 * Time: 15:25
 */
class LogController extends BaseController {

    public function indexAction(){
    }

    public function listAction(){
        $page=$this->getQuery('page',1);
        $limit=$this->getQuery('limit',20);
        $time=$this->getQuery('time','');
        if($time!=''){
            $time=explode(' - ',$time);
        }
        $LogModel=new LogModel();
        $Log=$LogModel->getLog($time,$page,$limit);
        if(count($Log['count'])<=0){
            return gf_ajax_error('无用户');
        }
        return gf_ajax_success($Log['log'],array('count'=>$Log['count']));
    }


    /**
     * 删除
     */
    function delAction(){
        $LogModel=new LogModel();
        $status=$LogModel->delLog(date('Y-m-d H:i:s',time()-30*24*60*60));
        if($status){
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

}