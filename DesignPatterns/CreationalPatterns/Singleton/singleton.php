<?php

class DBConnection {

    private function __construct()
    {
        echo "New object created!" . "\n";
    }

    public static function getInstance() {
        static $instance = null;
        if ($instance == null) {
            //$instance = new DBConnection();
            $instance = new static();
        }
        else {
            echo "Using same object." . "\n";
        }
        return $instance;
    }
}

$objA = DBConnection::getInstance();
$objB = DBConnection::getInstance();
$objC = DBConnection::getInstance();