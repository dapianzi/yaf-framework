<?php

/**
 * 工单操作
 * @Author: Carl
 * @Since: 2018-04-04 14:05
 * Created by PhpStorm.
 */
class OtrsModel extends DbModel {

    public $table = 'article';
    public $confName = 'otrs';
    public $pk = 'id';

    /**
     * $ticket id or tn
     */
    public function getArticleHtml($ticket) {
        $sql = "SELECT aa.content html,a.a_body text,a.a_subject subject FROM ticket t "
            ." LEFT JOIN article a ON t.id=a.ticket_id "
            ." LEFT JOIN article_attachment aa ON a.id=aa.article_id "
            ." WHERE t.id=? OR t.tn=? AND a.article_type_id=1 ";
        return $this->getRow($sql, array($ticket, $ticket));
    }

    function getticketInfo($time){
        $sql='SELECT * FROM otrs5.ticket_history where history_type_id=44 and ticket_id not in (select ticket_id from ws_php_develop.ws_otrs_upgrade_log) and create_time>? order by id desc';
        $ticketUpgradeInfo=$this->getRow($sql, array($time));
        if($ticketUpgradeInfo){
            $ticket_id=$ticketUpgradeInfo['ticket_id'];
            $ticketInfo=$this->ticketInfo($ticket_id);
            $sla=$this->getSlaTime($ticketInfo['sla_id']);
            $ascription=$this->customerInfo($ticketInfo['customer_user_id']);
            $Operator=$this->getOperator($ticket_id);
            $room=$this->getRoom($ticketInfo['queue_id']);
            $timeout=$ticketInfo['escalation_solution_time'];
            $time=round(($timeout-time())/60);
            if($room){
                $roomName=$room['name'];
                $roomUser=$room['responsible'];
            }else{
                $roomName='';
                $roomUser='';
            }
            return array(
                'title'=>'Ticket#'.$ticketInfo['tn'].' — '.$ticketInfo['title'].' <a href=\'http://hd.765.com.cn/otrs/index.pl?Action=AgentTicketZoom;TicketID='.$ticket_id.'\'>查看状态</a>',
                'createTime'=>$ticketInfo['create_time'],
                'sla'=>$sla,
                'ascription'=>$ascription,
                'Operator'=>$Operator,
                'roomUser'=>$roomUser,
                'roomName'=>$roomName,
                'time'=>$time,
                'ticket_id'=>$ticket_id
            );
        }else{
            return false;
        }
    }

    function ticketInfo($ticket_id){
        $sql = "SELECT tn,title,queue_id,sla_id,customer_id,customer_user_id,escalation_solution_time,create_time_unix,create_time FROM otrs5.ticket where id=?";
        return $this->getRow($sql, array($ticket_id));
    }

    function getSlaTime($sla_id){
        $sql='SELECT solution_time FROM otrs5.sla where id=?';
        return $this->getColumn($sql, array($sla_id));
    }

    function customerInfo($customer_user_id){
        $sql='SELECT name FROM otrs5.customer_company where customer_id=?';
        return $this->getColumn($sql, array($customer_user_id));
    }

    function getOperator($ticket_id){
        $sql='SELECT create_by FROM otrs5.ticket_history where history_type_id=15 and ticket_id =? and name="%%Note"';
        $create_by=$this->getColumn($sql, array($ticket_id));
        if($create_by){
            $sql='SELECT concat(last_name,first_name) FROM otrs5.users where id=?';
            return $this->getColumn($sql, array($create_by));
        }else{
            return '';
        }
    }

    function getRoom($queue_id){
        $sql='SELECT name FROM otrs5.queue where id=?';
        $name=$this->getColumn($sql, array($queue_id));
        if($name){
            $sql='SELECT responsible FROM ws_php_develop.ws_otrs_room_responsible where room=?';
            return array(
                'name'=>$name,
                'responsible'=>$this->getColumn($sql, array($name)),
            );
        }else{
            return false;
        }
    }

    function getWechatUser(){
        $sql='SELECT wechat FROM ws_php_develop.ws_otrs_wechat_user where status=1;';
        return $this->getAll($sql);
    }

    function addLog($ticket_id){
        $this->insert('ws_php_develop.ws_otrs_upgrade_log', array('ticket_id'=>$ticket_id));
    }
}