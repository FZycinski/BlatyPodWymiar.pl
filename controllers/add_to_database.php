1<?php
require_once 'DatabaseConnection.php';

class DatabaseManager {
    private $connection;

    public function __construct() {
        $this->connection = DatabaseConnection::getConnection();
    }

    public function addDataToDatabase($order_id, $kind_of_wood, $dimensions, $is_varnished, $is_oiled, $is_milled, $order_status, $order_deadline, $comments, $phone_number, $price, $source, $update_datetime, $create_datetime) {
        $order_id = $this->connection->real_escape_string($order_id);
        $kind_of_wood = $this->connection->real_escape_string($kind_of_wood);
        $dimensions = $this->connection->real_escape_string($dimensions);
        $comments = $this->connection->real_escape_string($comments);
        $source = $this->connection->real_escape_string($source);

        $sql = "INSERT INTO orders_archive (order_id, kind_of_wood, dimensions, is_varnished, is_oiled, is_milled, order_status, order_deadline, comments, phone_number, price, source, update_datetime, create_datetime) 
                VALUES ('$order_id', '$kind_of_wood', '$dimensions', '$is_varnished', '$is_oiled', '$is_milled', '$order_status', '$order_deadline', '$comments', '$phone_number', '$price', '$source', '$update_datetime', '$create_datetime')";

        if ($this->connection->query($sql) === TRUE) {
            echo "Dane zostały dodane do bazy danych.";
        } else {
            echo "Błąd: " . $sql . "<br>" . $this->connection->error;
        }
    }
}

$databaseManager = new DatabaseManager();

if (isset($_POST['submit'])) {
    $order_id = 12345;
    $kind_of_wood = "Dąb";
    $dimensions = "10 x 10 x 3,8cm";
    $is_varnished = 1;
    $is_oiled = 0;
    $is_milled = 1;
    $order_status = 1;
    $order_deadline = "2024-05-31";
    $comments = "Brak komentarza";
    $phone_number = 123456789;
    $price = 99.99;
    $source = "API";
    $update_datetime = date("Y-m-d H:i:s");
    $create_datetime = date("Y-m-d H:i:s");

    $databaseManager->addDataToDatabase($order_id, $kind_of_wood, $dimensions, $is_varnished, $is_oiled, $is_milled, $order_status, $order_deadline, $comments, $phone_number, $price, $source, $update_datetime, $create_datetime);
}
?>
