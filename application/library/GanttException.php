<?php

/**
 *
 * @Author: Carl
 * @Since: 2017-11-22 18:38
 * Created by PhpStorm.
 */
class GanttException extends Yaf_Exception {

    public function __construct($message="") {
        $message = $this->msg($message);
        parent::__construct($message, GANTT_EXCEPTION);
    }

    private function msg($msg) {
        return $msg;
    }

}