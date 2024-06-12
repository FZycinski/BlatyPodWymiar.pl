<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['allFormData'])) {
    $orderedItems = json_decode($_POST['allFormData'], true);
}
?>
<div>
    <h2>Złóż zamówienie</h2>
    <?php if (!empty($orderedItems)) : ?>
    <h3>Twoje zamówione przedmioty:</h3>
    <ul>
        <?php 
        $totalPriceNonAllegroSum = 0;
        foreach ($orderedItems as $index => $item): 
            $totalPriceNonAllegroSum += $item['totalPriceNonAllegro'];
        ?>
            <li>
                <strong>Przedmiot <?php echo $index + 1; ?>:</strong><br>
                Typ drewna: <?php echo htmlspecialchars($item['woodType']); ?><br>
                Grubość: <?php echo htmlspecialchars($item['thickness']); ?> mm<br>
                Długość: <?php echo htmlspecialchars($item['length']); ?> cm<br>
                Szerokość: <?php echo htmlspecialchars($item['width']); ?> cm<br>
                Liczba sztuk: <?php echo htmlspecialchars($item['piece']); ?><br>
                Lakierowanie: <?php echo $item['varnishChecked'] ? 'Tak' : 'Nie'; ?><br>
                Bejcowanie: <?php echo $item['stainChecked'] ? 'Tak' : 'Nie'; ?><br>
                Olejowanie: <?php echo $item['oilChecked'] ? 'Tak' : 'Nie'; ?><br>
                Frezowanie: <?php echo $item['millChecked'] ? 'Tak' : 'Nie'; ?><br>
                Cena poza Allegro: <?php echo $item['totalPriceNonAllegro']; ?> zł<br>
            </li>
        <?php endforeach; ?>
        Cena całkowita poza Allegro: <?php echo $totalPriceNonAllegroSum; ?> zł<br> <!-- Wyświetlenie sumy -->
    </ul>
<?php endif; ?>


    <form id="orderForm" action="../controllers/submit_order.php" method="post">
        <label for="name">Imię i nazwisko:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Adres email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Numer telefonu:</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="address">Ulica:</label>
        <input type="text" id="address" name="address" required><br>

        <label for="housenumber">Nr domu:</label>
        <input type="text" id="housenumber" name="housenumber" required><br>

        <label for="city">Miasto:</label>
        <input type="text" id="city" name="city" required><br>

        <label for="zip">Kod pocztowy:</label>
        <input type="text" id="zip" name="zip" required><br>
            <p>Dane do faktury(jeśli dotyczy):</p>
        <label for="invoice_company_name">Nazwa firmy:</label>
        <input type="text" id="invoice_company_name" name="invoice_company_name"><br>

        <label for="invoice_address_street">Ulica:</label>
        <input type="text" id="invoice_address_street" name="invoice_address_street"><br>

        <label for="invoice_address_zipCode">Kod pocztowy:</label>
        <input type="text" id="invoice_address_zipCode" name="invoice_address_zipCode"><br>

        <label for="invoice_address_city">Miejscowość:</label>
        <input type="text" id="invoice_address_city" name="invoice_address_city"><br>

        <label for="company_taxId">NIP:</label>
        <input type="text" id="company_taxId" name="company_taxId"><br>
        
        <label for="comments">Dodatkowe uwagi:</label>
        <textarea id="comments" name="comments" rows="4"></textarea><br>
        <input type="hidden" name="orderedItems" value='<?php echo json_encode($orderedItems); ?>'>
        <input type="submit" value="Złóż zamówienie">
    </form>
</div>