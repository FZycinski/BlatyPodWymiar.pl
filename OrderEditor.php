<?php

require_once 'config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();

class OrderEditor {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function getOrderById($order_id) {
        $sql = "SELECT * FROM orders WHERE order_id = $order_id";
        $result = $this->mysqli->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function updateOrder($order_id, $formData) {
        $kind_of_wood = isset($formData['kind_of_wood']) ? $formData['kind_of_wood'] : '';
        $dimensions = isset($formData['dimensions']) ? $formData['dimensions'] : '';
        $is_varnished = isset($formData['is_varnished']) ? 1 : 0;
        $is_oiled = isset($formData['is_oiled']) ? 1 : 0;
        $is_milled = isset($formData['is_milled']) ? 1 : 0;
        $phone_number = isset($formData['phone_number']) ? $formData['phone_number'] : '';
        $price = isset($formData['price']) ? $formData['price'] : '';
        $order_status = isset($formData['order_status']) ? $formData['order_status'] : '';
        $source = isset($formData['source']) ? $formData['source'] : '';
        $order_deadline = isset($formData['order_deadline']) ? $formData['order_deadline'] : '';
        $comments = isset($formData['comments']) ? $formData['comments'] : '';
        $update_datetime = date("Y-m-d H:i:s");

        $sql = "UPDATE orders SET 
                kind_of_wood = '{$kind_of_wood}', 
                dimensions = '{$dimensions}', 
                is_varnished = '{$is_varnished}', 
                is_oiled = '{$is_oiled}', 
                is_milled = '{$is_milled}', 
                phone_number = '{$phone_number}',
                price = '{$price}', 
                order_status = '{$order_status}',
                source = '{$source}',
                order_deadline = '{$order_deadline}', 
                comments = '{$comments}',
                update_datetime = '{$update_datetime}' 
                WHERE order_id = '{$order_id}'";
    
        if ($this->mysqli->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
}
?>
<script>

</script>