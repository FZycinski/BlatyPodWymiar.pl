<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

include_once("views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}

$config = include('config/api_config.php');
define('CLIENT_ID', $config['client_id']);
define('CLIENT_SECRET', $config['client_secret']);
define('REDIRECT_URI', 'https://test.blatypodwymiar.pl/views/ShippingController.php');
define('AUTH_URL', 'https://allegro.pl/auth/oauth/authorize');
define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

require_once 'config/DatabaseConnection.php';
require_once 'models/ShippingModel.php';
require_once 'controllers/ShippingController.php';

$mysqli = DatabaseConnection::getConnection();
if ($mysqli) {
    echo "Połączenie z bazą danych nawiązane.<br>";
} else {
    echo "Błąd połączenia z bazą danych.<br>";
}
$model = new ShippingModel($mysqli);
$controller = new ShippingController($model);
$controller->main();

?>
