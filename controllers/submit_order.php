<?php
print_r(    $orderedItems = json_decode($_POST['allFormData'], true).
$name = $_POST['name'].
$email = $_POST['email'].
$phone = $_POST['phone'].
$delivery_street = $_POST['address'] . ' ' . $_POST['housenumber'].
$delivery_city = $_POST['city'].
$delivery_zipCode = $_POST['zip'].
$invoice_street = $_POST['invoice_address_street'].
$invoice_city = $_POST['invoice_address_city'].
$invoice_zipCode = $_POST['invoice_address_zipCode'].
$company_name = $_POST['invoice_company_name'].
$company_taxId = ''.
$total_price = 0);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['allFormData'])) {
    $orderedItems = json_decode($_POST['allFormData'], true);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $delivery_street = $_POST['address'] . ' ' . $_POST['housenumber'];
    $delivery_city = $_POST['city'];
    $delivery_zipCode = $_POST['zip'];
    $invoice_street = $_POST['invoice_address_street'];
    $invoice_city = $_POST['invoice_address_city'];
    $invoice_zipCode = $_POST['invoice_address_zipCode'];
    $company_name = $_POST['invoice_company_name'];
    $company_taxId = '';
    $total_price = 0;

    foreach ($orderedItems as $item) {
        $total_price += $item['totalPriceNonAllegro'];
    }
    require_once 'config/DatabaseConnection.php';

    $mysqli = DatabaseConnection::getConnection();
    
    $sql_user = "INSERT INTO potential_order_users (email, firstName, lastName, phoneNumber, delivery_street, delivery_city, delivery_zipCode, invoice_street, invoice_city, invoice_zipCode, company_name, company_taxId, total_price) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param("ssssssssssssd", $email, $name, $name, $phone, $delivery_street, $delivery_city, $delivery_zipCode, $invoice_street, $invoice_city, $invoice_zipCode, $company_name, $company_taxId, $total_price);
    $stmt_user->execute();

    $user_id = $stmt_user->insert_id;

    $sql_order = "INSERT INTO potential_orders (user_id, kind_of_wood, thickness, length, width, price, is_varnished, is_oiled, is_milled) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_order = $mysqli->prepare($sql_order);

    foreach ($orderedItems as $item) {
        $kind_of_wood = $item['woodType'];
        $thickness = $item['thickness'];
        $length = $item['length'];
        $width = $item['width'];
        $price = $item['totalPriceNonAllegro'];
        $is_varnished = $item['varnishChecked'] ? 1 : 0;
        $is_oiled = $item['oilChecked'] ? 1 : 0;
        $is_milled = $item['millChecked'] ? 1 : 0;

        $stmt_order->bind_param("isddddiii", $user_id, $kind_of_wood, $thickness, $length, $width, $price, $is_varnished, $is_oiled, $is_milled);
        $stmt_order->execute();
    }

    $stmt_user->close();
    $stmt_order->close();

    echo "Zamówienie zostało złożone pomyślnie!";
} else {
    echo "Błąd: niepoprawne dane!";
}
?>
