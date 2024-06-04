<?php

class Order {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders";
        return $this->mysqli->query($sql);
    }

}