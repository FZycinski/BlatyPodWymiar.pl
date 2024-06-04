<?php

include_once 'config/DatabaseConnection.php';
$mysqli = DatabaseConnection::getConnection();

class OrdersAchiver{
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function moveOrdersToArchive() {
        $sql1 = "INSERT INTO orders_archive 
        SELECT 
            order_id, 
            kind_of_wood, 
            dimensions, 
            is_varnished, 
            is_oiled, 
            is_milled, 
            order_status, 
            order_deadline, 
            comments, 
            phone_number, 
            price, 
            source, 
            NOW() AS update_datetime,
            create_datetime 
        FROM orders 
        WHERE order_status = 3";

        $sql2 = "UPDATE additional_order_data 
                 SET order_id_copy = order_id, order_id = NULL 
                 WHERE order_id IN (SELECT order_id FROM orders WHERE order_status = 3)";

        $sql3 = "DELETE FROM orders WHERE order_status = 3";

        try {
            $this->mysqli->begin_transaction();

            $result1 = $this->mysqli->query($sql1);
        
            if ($result1 === TRUE) {
                $result2 = $this->mysqli->query($sql2);

                if ($result2 === TRUE) {
                    $result3 = $this->mysqli->query($sql3);

                    if ($result3 === TRUE) {
                        $this->mysqli->commit();
                        echo "Transakcja zakończona sukcesem.";
                    } else {
                        $this->mysqli->rollback();
                        echo "Trzecie zapytanie nie powiodło się. Transakcja została wycofana: " . $this->mysqli->error;
                    }
                } else {
                    $this->mysqli->rollback();
                    echo "Drugie zapytanie nie powiodło się. Transakcja została wycofana: " . $this->mysqli->error;
                }
            } else {
                $this->mysqli->rollback();
                echo "Pierwsze zapytanie nie powiodło się. Transakcja została wycofana: " . $this->mysqli->error;
            }
        } catch (Exception $e) {
            $this->mysqli->rollback();
            echo "Wystąpił błąd: " . $e->getMessage() . ". Transakcja została wycofana.";
        }
        
        DatabaseConnection::closeConnection();
    }
}

?>
