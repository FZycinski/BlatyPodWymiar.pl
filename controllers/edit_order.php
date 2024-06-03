<!DOCTYPE html>
<html>

<head>
    <title>Edytuj zamówienie</title>
</head>

<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $access_token = $_POST['access_token'];
        $order_id = $_POST['order_id'];
        $order_status = $_POST['order_status'];
        $order_payment_type = $_POST['order_payment_type'];
        $order_paid_amount = $_POST['order_paid_amount'];
        $buyer_email = $_POST['buyer_email'];
        $buyer_login = $_POST['buyer_login'];
        $delivery_address_firstName = $_POST['delivery_address_firstName'];
        $delivery_address_lastName = $_POST['delivery_address_lastName'];
        $delivery_address_companyName = $_POST['delivery_address_companyName'];
        $delivery_address_phoneNumber = $_POST['delivery_address_phoneNumber'];
        $delivery_address_street = $_POST['delivery_address_street'];
        $delivery_address_city = $_POST['delivery_address_city'];
        $delivery_address_zipCode = $_POST['delivery_address_zipCode'];
        $delivery_method_name = $_POST['delivery_method_name'];
        $delivery_cost_amount = $_POST['delivery_cost_amount'];
        $delivery_cost_currency = $_POST['delivery_cost_currency'];
        $delivery_time_to = $_POST['delivery_time_to'];
        $invoice_address_street = isset($_POST['invoice_address_street']) ? $_POST['invoice_address_street'] : "";
        $invoice_address_zipCode = isset($_POST['invoice_address_zipCode']) ? $_POST['invoice_address_zipCode'] : "";
        $invoice_address_city = isset($_POST['invoice_address_city']) ? $_POST['invoice_address_city'] : "";
        $invoice_company_name = isset($_POST['invoice_company_name']) ? $_POST['invoice_company_name'] : "";
        $invoice_company_taxId = isset($_POST['invoice_company_taxId']) ? $_POST['invoice_company_taxId'] : "";
        $message_to_seller = $_POST['message_to_seller'];
        $item_ids = $_POST['item_ids'];
        $item_names = $_POST['item_names'];
        $item_quantities = $_POST['item_quantities'];
        $item_prices = $_POST['item_prices'];
        $item_currencies = $_POST['item_currencies'];
        $payment_status = $_POST['payment_status'];
        $delivery_method_id = $_POST['delivery_method_id'];
    ?>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 800px;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                margin-top: 0;
            }

            form {
                max-width: 600px;
                margin: 0 auto;
            }

            input[type="text"],
            textarea {
                width: 100%;
                padding: 8px;
                margin-bottom: 10px;
                box-sizing: border-box;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }

            hr {
                border: none;
                border-top: 1px solid #ccc;
                margin: 20px 0;
            }

            .form-row {
                margin-bottom: 20px;
            }

            .form-row label {
                display: inline-block;
                width: 150px;
            }

            .form-row input[type="text"] {
                width: 200px;
            }
        </style>
        <form method="post" action="save_edited_order.php">
            <input type="hidden" name="access_token" value="<?php echo $access_token; ?>">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="order_status" value="<?php echo $order_status; ?>">
            <input type="hidden" name="additional_order_id" value="<?php echo $additional_order_id; ?>">
            <input type="hidden" name="buyer_login" value="<?php echo $buyer_login; ?>">
            <input type="hidden" name="delivery_address_firstName" value="<?php echo $delivery_address_firstName; ?>">
            <input type="hidden" name="delivery_address_lastName" value="<?php echo $delivery_address_lastName; ?>">
            <input type="hidden" name="delivery_address_street" value="<?php echo $delivery_address_street; ?>">
            <input type="hidden" name="delivery_address_city" value="<?php echo $delivery_address_city; ?>">
            <input type="hidden" name="delivery_address_zipCode" value="<?php echo $delivery_address_zipCode; ?>">
            <input type="hidden" name="delivery_method_name" value="<?php echo $delivery_method_name; ?>">
            <input type="hidden" name="delivery_cost_amount" value="<?php echo $delivery_cost_amount; ?>">
            <input type="hidden" name="delivery_time_to" value="<?php echo $delivery_time_to; ?>">
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            <input type="hidden" name="delivery_method_id" value="<?php echo $delivery_method_id; ?>">
            <?php if ($order_payment_type != 'ONLINE') { ?>
                Typ płatności: <input type="text" name="order_payment_type" value="<?php echo $order_payment_type; ?>"><br>
            <?php } ?>

            Kwota zapłacona: <input type="text" name="order_paid_amount" value="<?php echo $order_paid_amount; ?>"><br>
            Email kupującego: <input type="text" name="buyer_email" value="<?php echo $buyer_email; ?>"><br>
            Numer telefonu: <input type="text" name="delivery_address_phoneNumber" value="<?php echo $delivery_address_phoneNumber; ?>"><br>

            Wiadomość do sprzedawcy: <br>
            <textarea name="message_to_seller" rows="4" cols="50"><?php echo $message_to_seller; ?></textarea><br>

            <?php foreach ($item_ids as $key => $item_id) { ?>
                Nazwa oferty: <input type="text" name="item_names[]" value="<?php echo $item_names[$key]; ?>"><br>
                Ilość: <input type="text" name="item_quantities[]" value="<?php echo $item_quantities[$key]; ?>"><br>
                Cena: <input type="text" name="item_prices[]" value="<?php echo $item_prices[$key]; ?>"><br>
                <hr>
            <?php } ?>

            <?php if (!empty($invoice_address_street)) { ?>
                Ulica (faktura): <input type="text" name="invoice_address_street" value="<?php echo $invoice_address_street; ?>"><br>
                Kod pocztowy (faktura): <input type="text" name="invoice_address_zipCode" value="<?php echo $invoice_address_zipCode; ?>"><br>
                Miasto (faktura): <input type="text" name="invoice_address_city" value="<?php echo $invoice_address_city; ?>"><br>
                Nazwa firmy (faktura): <input type="text" name="invoice_company_name" value="<?php echo $invoice_company_name; ?>"><br>
                NIP (faktura): <input type="text" name="invoice_company_taxId" value="<?php echo $invoice_company_taxId; ?>"><br>
            <?php } ?>
            <hr>
            <div class="form-row">
                <label for="package_length">Długość paczki:</label>
                <input type="text" name="package_length" id="package_length">
            </div>

            <div class="form-row">
                <label for="package_width">Szerokość paczki:</label>
                <input type="text" name="package_width" id="package_width">
            </div>

            <div class="form-row">
                <label for="package_height">Wysokość paczki:</label>
                <input type="text" name="package_height" id="package_height">
            </div>

            <div class="form-row">
                <label for="package_weight">Waga paczki:</label>
                <input type="text" name="package_weight" id="package_weight">
            </div>
            <hr>
            Wpisz wymiar: <input type="text" name="dimensions"><br>
            Lakierowanie: <input type="checkbox" name="is_varnished"><br>
            Olejowanie: <input type="checkbox" name="is_oiled"><br>
            Frezowanie: <input type="checkbox" name="is_milled"><br>
            <input type="hidden" name="order_status" value="0">
            <input type="hidden" name="source" value="Allegro">
            Termin wykonania: <input type="date" name="order_deadline" value="<?php echo date('Y-m-d', strtotime('+13 days')); ?>"><br>
            Komentarz: <textarea name="comments"></textarea><br>
            <input type="submit" value="Wrzuć zamówienie do tabeli">
        </form>

    <?php } else {
        echo "Błąd: Brak danych przesłanych do formularza.";
    }
    ?>

</body>

</html>