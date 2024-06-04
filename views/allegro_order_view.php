<?php
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();
include_once("views/structure/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login_view.php");
    exit;
}

$config = include('../config/api_config.php');

define('CLIENT_ID', $config['client_id']);
define('CLIENT_SECRET', $config['client_secret']);
define('REDIRECT_URI', $config['redirect_uri']);
define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

function getCurl($headers, $content)
{
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => TOKEN_URL,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $content
    ));
    return $ch;
}
function getAccessToken($authorization_code)
{
    $authorization = base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);
    $authorization_code = urlencode($authorization_code);
    $headers = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");
    $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=" . REDIRECT_URI;
    $ch = getCurl($headers, $content);
    $tokenResult = curl_exec($ch);
    $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($tokenResult === false || $resultCode !== 200) {
        exit("Something went wrong $resultCode $tokenResult");
    }
    return json_decode($tokenResult)->access_token;
}

function fetchOrders($access_token)
{
    $url = 'https://api.allegro.pl/order/checkout-forms';
    $headers = array(
        "Authorization: Bearer {$access_token}",
        "Accept: application/vnd.allegro.public.v1+json",
        "Content-Type: application/vnd.allegro.public.v1+json"
    );

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true
    ));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $checkoutForms = json_decode($response, true);
        echo '<style>
        .order-table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .order-table th, .order-table td {
            padding: 8px;
            border: 1px solid #dddddd;
        }
        .order-table th {
            background-color: #f2f2f2;
        }
        .order-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .order-table tr:last-child td {
            border-bottom: 2px solid #000;
        }
        strong {
            font-weight: bold;
        }
        .edit-form {
            margin-top: 20px;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            background-color: #f5f5f5;
        }
        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }
        .edit-form input[type="text"],
        .edit-form input[type="hidden"] {
            width: calc(100% - 18px);
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .button-container button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
            margin-top: 10px;
        }
        .button-container button:hover {
            background-color: #45a011;
        }
    </style>';
    
    
    
    
    echo '<table class="order-table">';
    foreach ($checkoutForms['checkoutForms'] as $form) {
        echo '<tr>';
        echo '<th>Status zamówienia: '. translateStatus($form['fulfillment']['status']) .'</th>';
        echo '<th>Status płatności: ' . translateFormStatus($form['status']) . '</th>';
        echo '</tr>';

        foreach ($form['lineItems'] as $item) {
            echo '<tr>';
            echo '<td>Nazwa oferty: ';
            echo interpretOfferName($item['offer']['name']) . ' x ' . $item['quantity'] . ' szt.</td>';
            echo '<td>Cena za sztukę: ';
            echo $item['price']['amount'] . ' ' . $item['price']['currency'] . '</td>';
            echo '</tr>';


        }
        echo '<tr>';
        echo '<td>Płatność: ';
        echo $form['payment']['type'] . ', Zapłacone: ' . $form['payment']['paidAmount']['amount'] . ' ' . $form['payment']['paidAmount']['currency'] . '</td>';
        echo 'Koszt:' . $form['cost']['amount'];
        echo '<td>Wiadomość:';
        echo '<br>' . $form['messageToSeller'] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>' . $form['buyer']['email'] . '<br>' . $form['buyer']['login'] . '<br>' . $form['delivery']['address']['firstName'] . ' ' . $form['delivery']['address']['lastName'] . '<br>' . ($form['delivery']['address']['companyName'] ? 'Nazwa firmy: ' . $form['delivery']['address']['companyName'] . '<br>' : '') . $form['delivery']['address']['phoneNumber'] . '<br>' . $form['delivery']['address']['street'] . '<br>' . $form['delivery']['address']['city'] . '<br>' . $form['delivery']['address']['zipCode'] . '</td>';
        echo '<td>' . $form['delivery']['method']['name'] . '<br>Koszt dostawy: ' . $form['delivery']['cost']['amount'] . ' ' . $form['delivery']['cost']['currency'] . '<br>Data dostawy: do ' . $form['delivery']['time']['to'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            if ($form['invoice']['required']) {
                echo '<td>Dane do faktury: </td>';
                echo '<td>' . $form['invoice']['address']['street'] . ', ' . $form['invoice']['address']['zipCode'] . ' ' . $form['invoice']['address']['city'];
                echo '<br>' . $form['invoice']['address']['company']['name'];
                echo '<br>NIP: ' . $form['invoice']['address']['company']['taxId'] . '</td>';
            }
            echo '</tr>';



            echo '<tr>';
            echo '<td colspan="2">';
            echo '<form method="post" action="../controllers/edit_order.php">';
            echo '<input type="hidden" name="access_token" value="' . $access_token . '">';
            echo '<input type="hidden" name="access_token" value="' . $form['access_token'] . '">';
            echo '<input type="hidden" name="order_id" value="' . $form['id'] . '">';
            echo '<input type="hidden" name="delivery_method_id" value="' . $form['delivery']['method']['id'] . '">';
            foreach ($form['lineItems'] as $item) {
                echo '<input type="hidden" name="item_ids[]" value="' . $item['id'] . '">';
                echo '<input type="hidden" name="item_names[]" value="' . interpretOfferName($item['offer']['name']) . '">';
                echo '<input type="hidden" name="item_quantities[]" value="' . $item['quantity'] . '">';
                echo '<input type="hidden" name="item_prices[]" value="' . $item['price']['amount'] . '">';
            }
            echo '<input type="hidden" name="order_status" value="' . $form['status'] . '">';
            echo '<input type="hidden" name="order_payment_type" value="' . $form['payment']['type'] . '">';
            echo '<input type="hidden" name="order_paid_amount" value="' . $form['payment']['paidAmount']['amount'] . '">';
            echo '<input type="hidden" name="buyer_email" value="' . $form['buyer']['email'] . '">';
            echo '<input type="hidden" name="buyer_login" value="' . $form['buyer']['login'] . '">';
            echo '<input type="hidden" name="delivery_address_firstName" value="' . $form['delivery']['address']['firstName'] . '">';
            echo '<input type="hidden" name="delivery_address_lastName" value="' . $form['delivery']['address']['lastName'] . '">';
            echo '<input type="hidden" name="delivery_address_phoneNumber" value="' . $form['delivery']['address']['phoneNumber'] . '">';
            echo '<input type="hidden" name="delivery_address_street" value="' . $form['delivery']['address']['street'] . '">';
            echo '<input type="hidden" name="delivery_address_city" value="' . $form['delivery']['address']['city'] . '">';
            echo '<input type="hidden" name="delivery_address_zipCode" value="' . $form['delivery']['address']['zipCode'] . '">';
            echo '<input type="hidden" name="delivery_method_name" value="' . $form['delivery']['method']['name'] . '">';
            echo '<input type="hidden" name="delivery_cost_amount" value="' . $form['delivery']['cost']['amount'] . '">';
            echo '<input type="hidden" name="delivery_time_to" value="' . $form['delivery']['time']['to'] . '">';
            echo '<input type="hidden" name="delivery_time_top" value="' . $form['delivery']['time']['to'] . '">';
            
            if ($form['invoice']['required']) {
                echo '<input type="hidden" name="invoice_address_street" value="' . $form['invoice']['address']['street'] . '">';
                echo '<input type="hidden" name="invoice_address_zipCode" value="' . $form['invoice']['address']['zipCode'] . '">';
                echo '<input type="hidden" name="invoice_address_city" value="' . $form['invoice']['address']['city'] . '">';
                echo '<input type="hidden" name="invoice_company_name" value="' . $form['invoice']['address']['company']['name'] . '">';
                echo '<input type="hidden" name="invoice_company_taxId" value="' . $form['invoice']['address']['company']['taxId'] . '">';
            }
            echo '<input type="hidden" name="message_to_seller" value="' . $form['messageToSeller'] . '">';
            echo '<input type="hidden" name="payment_status" value="' . translateFormStatus($form['status']) . '">';
            echo '<div class="button-container">';
            echo '<button type="submit" name="edit_order">Wrzuć zamówienie</button></form>';
            if ($form['invoice']['required']) {
                echo '<br><form method="post" action="../controllers/invoice/invoice_creator.php">';
                echo '<input type="hidden" name="delivery_cost_amount" value="' . $form['delivery']['cost']['amount'] . '">';
                echo '<input type="hidden" name="order_paid_amount" value="' . $form['payment']['paidAmount']['amount'] . '">';
                echo '<input type="hidden" name="access_token" value="' . $access_token . '">';
                echo '<input type="hidden" name="invoice_address_street" value="' . $form['invoice']['address']['street'] . '">';
                echo '<input type="hidden" name="invoice_address_zipCode" value="' . $form['invoice']['address']['zipCode'] . '">';
                echo '<input type="hidden" name="invoice_address_city" value="' . $form['invoice']['address']['city'] . '">';
                echo '<input type="hidden" name="invoice_company_name" value="' . $form['invoice']['address']['company']['name'] . '">';
                echo '<input type="hidden" name="invoice_company_taxId" value="' . $form['invoice']['address']['company']['taxId'] . '">';
                echo '<input type="hidden" name="delivery_method_id" value="' . $form['delivery']['method']['id']. '">';
                echo '<button type="submit" name="create_invoice">Wygeneruj fakturę</button>';
                echo '</form>';
            }
            echo '</div>';
        }

        echo '<br><br></table>';
    } else {
        echo "Błąd pobierania danych: {$http_code}";
    }
}


if (isset($_GET["code"])) {
    $access_token = getAccessToken($_GET["code"]);

    fetchOrders($access_token);
} else {
    exit("Authorization code is missing.");
}


function translateStatus($status)
{
    switch ($status) {
        case 'NEW':
            return 'Nowe';
        case 'PROCESSING':
            return 'W realizacji';
        case 'READY_FOR_SHIPMENT':
            return 'Gotowe do wysyłki';
        case 'READY_FOR_PICKUP':
            return 'Gotowe do odbioru';
        case 'SENT':
            return 'Wysłane';
        case 'PICKED_UP':
            return 'Odebrane';
        case 'CANCELLED':
            return 'Anulowane';
        case 'SUSPENDED':
            return 'Zawieszone';
        default:
            return 'Nieznany';
    }
}
function translateFormStatus($status)
{
    switch ($status) {
        case 'BOUGHT':
            return 'Nie opłacone';
        case 'FILLED_IN':
            return 'Nie opłacone, w oczekiwaniu';
        case 'READY_FOR_PROCESSING':
            return 'Opłacone';
        case 'CANCELLED':
            return 'Anulowane';
        default:
            return 'Nieznany';
    }
}
function interpretOfferName($offerName)
{
    switch ($offerName) {
        case 'Blat jesionowy pod wymiar 10 x 10 x 3,8cm':
            return 'Jesion 3,8cm';
        case 'Blat Jesionowy pod wymiar 10 x 10 x 2,7cm':
            return 'Jesion 2,7cm';
        case 'Blat Dębowy pod wymiar 10 x 10 x 3,8cm':
            return 'Dąb 3,8cm';
        case 'Blat Dębowy pod wymiar 10 x 10 x 2,7cm':
            return 'Dąb 2,7cm';
        case 'Blat Dębowy pod wymiar 10 x 10 x 1,9cm':
            return 'Dąb 1,9cm';
        case 'Blat bukowy pod wymiar 10 x 10 x 3,8 cm':
            return 'Buk 3,8cm';
        case 'Blat Bukowy pod wymiar 10 x 10 x 1,9cm':
            return 'Buk 1,9cm';
        case 'Blat bukowy pod wymiar 10 x 10 x 2,7cm':
            return 'Buk 2,7cm';
        default:
            return $offerName;
    }
}
