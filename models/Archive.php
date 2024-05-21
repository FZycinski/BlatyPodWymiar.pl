
    <?php

class Archive {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders_archive";
        return $this->mysqli->query($sql);
    }
}