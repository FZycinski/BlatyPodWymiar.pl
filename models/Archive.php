
    <?php

class Archive {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders WHERE order_id = 3";
        return $this->mysqli->query($sql);
    }
}