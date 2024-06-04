<?php
include_once '../config/DatabaseConnection.php';
$mysqli = DatabaseConnection::getConnection();

class AddOrder {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function addOrder($kind_of_wood, $dimensions, $is_varnished, $is_oiled, $is_milled, $phone_number, $price, $order_status, $source, $order_deadline, $comments) {
        $kind_of_wood = $this->mysqli->real_escape_string($kind_of_wood);
        $dimensions = $this->mysqli->real_escape_string($dimensions);
        $is_varnished = isset($is_varnished) ? 1 : 0;
        $is_oiled = isset($is_oiled) ? 1 : 0;
        $is_milled = isset($is_milled) ? 1 : 0;
        $phone_number = (int)$phone_number;
        $price = (float)$price;
        $order_status = (int)$order_status;
        $source = $this->mysqli->real_escape_string($source);
        $order_deadline = $this->mysqli->real_escape_string($order_deadline);
        $comments = $this->mysqli->real_escape_string($comments);
    
        $create_datetime = date("Y-m-d H:i:s");
    
        $sql = "INSERT INTO orders (kind_of_wood, dimensions, is_varnished, is_oiled, is_milled, phone_number, price, order_status, source, order_deadline, comments, create_datetime) VALUES ('$kind_of_wood', '$dimensions', $is_varnished, $is_oiled, $is_milled, $phone_number, $price, $order_status, '$source', '$order_deadline', '$comments', '$create_datetime')";
    
        if ($this->mysqli->query($sql) === TRUE) {
            return "New order added successfully";
        } else {
            return "Error adding order: " . $this->mysqli->error;
        }
    }
    

    public function closeConnection() {
        $this->mysqli->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $addOrder = new AddOrder($mysqli);
    $message = $addOrder->addOrder($_POST['kind_of_wood'], $_POST['dimensions'], $_POST['is_varnished'], $_POST['is_oiled'], $_POST['is_milled'], $_POST['phone_number'], $_POST['price'], $_POST['order_status'], $_POST['source'],$_POST['order_deadline'], $_POST['comments']);
    echo $message;
    $addOrder->closeConnection();

}
?>
<script>
alert("Zamówienie zostało dodane.");
window.location.href = "../index.php";
</script>
