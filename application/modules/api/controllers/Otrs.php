<?php

/**
 *
 * @Author: Carl
 * @Since: 2017/4/6 16:59
 * Created by PhpStorm.
 */
class OtrsController extends Yaf_Controller_Abstract {

    public function init() {
        $argv = $this->getRequest()->getParams();
        switch ($argv[1]) {
            case 1001:
                $r = $this->sayworld($argv);
                break;
            case 1002:
                $r = $this->doOtrsUpgradeNotify();
                break;
            default:
                $r = $this->sayhello($argv);
        }
        gf_ajax_success($r);
    }


    public function doOtrsUpgradeNotify(){
        echo "Do Otrs Upgrade Notify start..........\n";
        $OtrsModel=new OtrsModel();
        $time='2018-04-11 05:00:00';
        $touser=$OtrsModel->getWechatUser();
        foreach ($touser as $value){
            $touserlist[]=$value['wechat'];
        }
        $touser=implode('|',$touserlist);
        while(TRUE) {
            $ticketInfo=$OtrsModel->getticketInfo($time);
            if(!$ticketInfo){
                echo "Do Otrs Upgrade Notify No Data..........\n";
                sleep(1);
                continue;
            }else{
                echo "Do Otrs Upgrade Notify ticketInfo = ".json_encode($ticketInfo,JSON_UNESCAPED_UNICODE)."..........\n";
                if($ticketInfo['time']>0){
                    $txt="以下工单将在".$ticketInfo['time']."分钟后超时，请及时处理。\n";
                }else{
                    $txt="以下工单已超时，请及时处理。\n";
                }
                $content=$ticketInfo['roomName']." 工单升级告警\n".$txt."工单标题：".$ticketInfo['title']."\n下单时间：".$ticketInfo['createTime']."\nSLA时间：".$ticketInfo['sla']."min\n工单归属：".$ticketInfo['ascription']."\n操作人：".$ticketInfo['Operator']."\n机房负责人：".$ticketInfo['roomUser'];
                $re=$this->sendWechatMessage($content,$touser);
                echo "Do Otrs Upgrade Notify sendWechatMessage = ".json_encode($re,JSON_UNESCAPED_UNICODE)."..........\n";
                if($re['errcode']==0){
                    $OtrsModel->addLog($ticketInfo['ticket_id']);
                }
                unset($ticketInfo);
                sleep(1);
            }
        }
    }



    public function sendWechatMessage($content,$touser){
        //$content="WS-GZ-M6机房工单升级告警\n以下工单将在10分钟后超时，请及时处理。\n工单标题：Ticket#2018041171000163 — WS-GZ-M7-FTX001 <a href='http://hd.765.com.cn/otrs/index.pl?Action=AgentTicketZoom;TicketID=2551'>查看状态</a>\n下单时间：2018-04-11  12:12\nSLA时间：60min\n工单归属：蛮蛮云\n操作人：李四\n机房负责人：张三";
        $access_token=$this->getToken('wx52769df7444a13bb','6FbxKSjcqQRhmtKb5VUPs8WOchDrvsnGhsuJSoZdANc');
        if($access_token==false){
            return false;
        }
        //$touser=implode('|',$this->userList);
        $Url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=".$access_token;
        $Data = array(
            "touser"=>'OuYangDongMing',                      // 企业号中的用户帐号，在zabbix用户Media中配置，如果配置不正常，将按部门发送。
            //"toparty"=>37,                        // 企业号中的部门id，群发时使用。
            "msgtype"=>"text",                      // 消息类型。
            "agentid"=>'1000018',                   // 企业号中的应用id。
            "text"=>array(
                "content"=>$content
            ),
            "safe"=> "0"
        );
        $re=gf_http_post($Url,json_encode($Data,JSON_UNESCAPED_UNICODE));
        $re=json_decode($re,true);
        return $re;
    }


    function getToken($Corpid,$Secret){
        $url="https://qyapi.weixin.qq.com/cgi-bin/gettoken";
        $data = array(
            "corpid"    =>$Corpid,
            "corpsecret"=>$Secret
        );
        $re=gf_http_get($url,$data);
        $re=json_decode($re,true);
        if($re['errcode']==0){
            $access_token=$re['access_token'];
            return $access_token;
        }else{
            //gf_ajax_error('get token error:'.$re['errmsg']);
            return false;
        }
    }




    function indexAction() {
        gf_ajax_success(array(
            'controller' => __CLASS__,
            'action' => __METHOD__,
            'params' => $this->getRequest()->getParams(),
        ));
    }


    public function sayhello($argv) {
        return $argv;
    }

    public function sayworld($argv) {
        while(TRUE) {
            echo 'cmd 1001';
            sleep(1);
        }
    }
}