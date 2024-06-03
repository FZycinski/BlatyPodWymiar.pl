<?php
require_once '../config/DatabaseConnection.php';

$mysqli = DatabaseConnection::getConnection();

// Pobieranie danych z $_POST
$access_token = $_POST['access_token'];
$order_id = $_POST['order_id'];
$order_status = $_POST['order_status'];
$order_payment_type = $_POST['order_payment_type'];
$order_paid_amount = $_POST['order_paid_amount'];
$buyer_email = $_POST['buyer_email'];
$buyer_login = $_POST['buyer_login'];
$delivery_address_firstName = $_POST['delivery_address_firstName'];
$delivery_address_lastName = $_POST['delivery_address_lastName'];
$delivery_address_phoneNumber = $_POST['delivery_address_phoneNumber'];
$delivery_address_street = $_POST['delivery_address_street'];
$delivery_address_city = $_POST['delivery_address_city'];
$delivery_address_zipCode = $_POST['delivery_address_zipCode'];
$delivery_method_name = $_POST['delivery_method_name'];
$delivery_cost_amount = $_POST['delivery_cost_amount'];
$delivery_time_to = $_POST['delivery_time_to'];
$message_to_seller = $_POST['message_to_seller'];
$item_ids = $_POST['item_ids'];
$item_names = $_POST['item_names'];
$item_quantities = $_POST['item_quantities'];
$item_prices = $_POST['item_prices'];
$invoice_address_street = $_POST['invoice_address_street'];
$invoice_address_zipCode = $_POST['invoice_address_zipCode'];
$invoice_address_city = $_POST['invoice_address_city'];
$invoice_company_name = $_POST['invoice_company_name'];
$invoice_company_taxId = $_POST['invoice_company_taxId'];
$dimensions = $_POST['dimensions'];
$is_varnished = isset($_POST['is_varnished']) ? 1 : 0;
$is_oiled = isset($_POST['is_oiled']) ? 1 : 0;
$is_milled = isset($_POST['is_milled']) ? 1 : 0;
$source = $_POST['source'];
$order_deadline = $_POST['order_deadline'];
$comments = $_POST['comments'];
$delivery_method_id = $_POST['delivery_method_id'];
$package_length = $_POST['package_length'];
$package_width = $_POST['package_width'];
$package_height = $_POST['package_height'];
$package_weight = $_POST['package_weight'];

$phone_number_without_prefix = str_replace("+48", "", $delivery_address_phoneNumber);
$phone_number_cleaned = preg_replace('/\s+/', '', $phone_number_without_prefix);

foreach ($item_names as $item) {
    $parts = explode(' ', $item);
    $kind_of_wood = $parts[0];
}

$mysqli->query("INSERT INTO orders (kind_of_wood, dimensions, is_varnished, is_oiled, is_milled, source, order_deadline, comments, order_status, phone_number, price, update_datetime, create_datetime) VALUES ('$kind_of_wood', '$dimensions', '$is_varnished', '$is_oiled', '$is_milled', '$source', '$order_deadline', '$comments', '0', '$phone_number_without_prefix', '$order_paid_amount', NOW(), NOW())");

$order_id = $mysqli->insert_id;

$stmt = $mysqli->prepare("INSERT INTO additional_order_data 
    (access_token, order_id, order_status, order_payment_type, order_paid_amount, buyer_email, buyer_login, delivery_address_firstName, delivery_address_lastName, delivery_address_phoneNumber, delivery_address_street, delivery_address_city, delivery_address_zipCode, delivery_method_name, delivery_cost_amount, delivery_time_to, message_to_seller, item_id, item_name, item_quantity, item_price, invoice_address_street, invoice_address_zipCode, invoice_address_city, invoice_company_name, invoice_company_taxId, delivery_method_id, package_length, package_width, package_height, package_weight) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sissdssssssssssssssssssssssdddd",
    $access_token,
    $order_id,
    $order_status,
    $order_payment_type,
    $order_paid_amount,
    $buyer_email,
    $buyer_login,
    $delivery_address_firstName,
    $delivery_address_lastName,
    $delivery_address_phoneNumber,
    $delivery_address_street,
    $delivery_address_city,
    $delivery_address_zipCode,
    $delivery_method_name,
    $delivery_cost_amount,
    $delivery_time_to,
    $message_to_seller,
    $item_id,
    $item_name,
    $item_quantity,
    $item_price,
    $invoice_address_street,
    $invoice_address_zipCode,
    $invoice_address_city,
    $invoice_company_name,
    $invoice_company_taxId,
    $delivery_method_id,
    $package_length,
    $package_width,
    $package_height,
    $package_weight
);

$stmt->execute();
$stmt->close();

$mysqli->close();
header("Location: /index.php");
exit;
