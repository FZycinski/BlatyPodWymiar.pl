<?php
require_once 'config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $mysqli->begin_transaction();

    try {
        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting order: " . $stmt->error);
        }

        $sql = "DELETE FROM additional_order_data WHERE order_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            throw new Exception("Error deleting additional order data: " . $stmt->error);
        }

        $mysqli->commit();
        echo "Order and associated data deleted successfully";

    } catch (Exception $e) {
        $mysqli->rollback();
        echo $e->getMessage();
    } finally {
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "Invalid order ID";
}
?>
<script>
alert("Zamówienie zostało usunięte.");
//window.location.href = "index.php";
</script>
