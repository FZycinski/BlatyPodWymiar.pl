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
define('REDIRECT_URI', $config['redirect_uri']);
define('AUTH_URL', 'https://allegro.pl/auth/oauth/authorize');
define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

require_once 'config/DatabaseConnection.php';
require_once 'models/ShippingModel.php';
require_once 'controllers/ShippingController.php';

$mysqli = DatabaseConnection::getConnection();
$controller = new ShippingController($mysqli);
$controller->main();
?>
