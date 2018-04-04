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

}