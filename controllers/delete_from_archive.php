<?php
require_once '../config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM orders_archive WHERE order_id = $id";

    if ($mysqli->query($sql) === TRUE) {
        echo "Order deleted successfully";
    } else {
        echo "Error deleting order: " . $mysqli->error;
    }
} else {
    echo "Invalid order ID";
}
?>
<script>
alert("Zamówienie zostało usunięte.");
window.location.href = "../views/archive_view.php";
</script>