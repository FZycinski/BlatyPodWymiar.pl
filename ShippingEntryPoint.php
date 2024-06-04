<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

include_once("views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}
if(isset($_POST['order_ids'])){
    $orderIds = $_POST['order_ids'];
    $orderIds = trim($orderIdsString, ',');
    $orderIdsArray = explode(',', $orderIdsString);

    $validOrderIds = array();

foreach ($orderIdsArray as $orderId) {
    if (is_numeric($orderId)) {
        $validOrderIds[] = $orderId;
    } else {
        echo "Cofnij i podaj liczby jeszcze raz";
    }
}
    $_SESSION['order_ids'] = $validOrderIds;
    echo "kaniuk";
}

$config = include('config/api_config.php');
define('CLIENT_ID', $config['client_id']);
define('CLIENT_SECRET', $config['client_secret']);
define('REDIRECT_URI', 'https://test.blatypodwymiar.pl/ShippingEntryPoint.php');
define('AUTH_URL', 'https://allegro.pl/auth/oauth/authorize');
define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

require_once 'config/DatabaseConnection.php';
require_once 'models/ShippingModel.php';
require_once 'controllers/ShippingController.php';

$mysqli = DatabaseConnection::getConnection();

$model = new ShippingModel($mysqli);
$controller = new ShippingController($model);
$controller->main();

?>


