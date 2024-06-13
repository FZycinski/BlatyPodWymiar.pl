<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderedItems'])) {
    $orderedItems = json_decode($_POST['orderedItems'], true);
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
    $company_taxId = $_POST['company_taxId'];
    $total_price = 0;

    foreach ($orderedItems as $item) {
        $total_price += $item['totalPriceNonAllegro'];
    }

    require_once '../config/DatabaseConnection.php';

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

    // Construct the email body
    $email_body = "<h1>Potwierdzenie Zamówienia</h1>";
    $email_body .= "<p>Dziękujemy za złożenie zamówienia. Oto szczegóły Twojego zamówienia:</p>";
    $email_body .= "<h2>Dane Klienta</h2>";
    $email_body .= "<p>Imię i Nazwisko: $name</p>";
    $email_body .= "<p>Email: $email</p>";
    $email_body .= "<p>Telefon: $phone</p>";
    $email_body .= "<h2>Adres Dostawy</h2>";
    $email_body .= "<p>Ulica: $delivery_street</p>";
    $email_body .= "<p>Miasto: $delivery_city</p>";
    $email_body .= "<p>Kod Pocztowy: $delivery_zipCode</p>";
    $email_body .= "<h2>Adres Faktury</h2>";
    $email_body .= "<p>Ulica: $invoice_street</p>";
    $email_body .= "<p>Miasto: $invoice_city</p>";
    $email_body .= "<p>Kod Pocztowy: $invoice_zipCode</p>";
    $email_body .= "<p>Nazwa Firmy: $company_name</p>";
    $email_body .= "<p>NIP: $company_taxId</p>";
    $email_body .= "<h2>Podsumowanie Zamówienia</h2>";

    foreach ($orderedItems as $index => $item) {
        $email_body .= "<h3>Pozycja " . ($index + 1) . "</h3>";
        $email_body .= "<p>Rodzaj Drewna: " . $item['woodType'] . "</p>";
        $email_body .= "<p>Grubość: " . $item['thickness'] . " mm</p>";
        $email_body .= "<p>Długość: " . $item['length'] . " cm</p>";
        $email_body .= "<p>Szerokość: " . $item['width'] . " cm</p>";
        $email_body .= "<p>Ilość: " . $item['piece'] . "</p>";
        $email_body .= "<p>Lakier: " . ($item['varnishChecked'] ? 'Tak' : 'Nie') . "</p>";
        $email_body .= "<p>Bejca: " . ($item['stainChecked'] ? 'Tak' : 'Nie') . "</p>";
        $email_body .= "<p>Olej: " . ($item['oilChecked'] ? 'Tak' : 'Nie') . "</p>";
        $email_body .= "<p>Frez: " . ($item['millChecked'] ? 'Tak' : 'Nie') . "</p>";
        $email_body .= "<p>Cena: " . $item['totalPriceNonAllegro'] . " zł</p>";
    }

    $email_body .= "<h3>Łączna Kwota: $total_price zł</h3>";

    require '../config/mail_config.php'; 

    $mail = createMailer();
    try {
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Zamówienie nr: ' . $user_id;
        $mail->Body    = $email_body;
        $mail->AltBody = strip_tags($email_body);

        $mail->send();
        echo 'Zamówienie zostało złożone pomyślnie! Powiadomienie email zostało wysłane.';
    } catch (Exception $e) {
        echo "Zamówienie zostało złożone pomyślnie, ale nie udało się wysłać powiadomienia email. Błąd: {$mail->ErrorInfo}";
    }
} else {
    echo "Błąd: niepoprawne dane!";
}
?>
