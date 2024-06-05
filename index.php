<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
include_once("views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}
require_once 'config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();
require_once 'models/Order.php';

$orderModel = new Order($mysqli);
$orders = $orderModel->getAllOrders();

include 'views/order_view.php';

require_once '../app/Controllers/CalculatorController.php';
require_once '../app/Models/Calculator.php';

use App\Controllers\CalculatorController;

$controller = new CalculatorController();

if (isset($_GET['action']) && $_GET['action'] === 'calculate') {
    $controller->handleRequest();
} else {
    $controller->showForm();
}
?>