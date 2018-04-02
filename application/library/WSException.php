<?php

/**
 *
 * @Author: Carl
 * @Since: 2017-11-22 18:38
 * Created by PhpStorm.
 */
class WSException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($this->wrap($message), WS_EXCEPTION, $previous);
    }

    public function wrap($msg) {
        return $msg;
    }

}