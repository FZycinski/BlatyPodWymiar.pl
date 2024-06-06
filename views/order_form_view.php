<?php if (!empty($orderedItems)) : ?>
    <h3>Twoje zamówione przedmioty:</h3>
    <ul>
      <?php foreach ($orderedItems as $index => $item): ?>
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

          <?php
            // Kalkulacja ceny poza Allegro dla danego elementu
            $woodType = $item['woodType'];
            $thickness = floatval($item['thickness']);
            $length = floatval($item['length']);
            $width = floatval($item['width']);
            $piece = intval($item['piece']);
            $varnishChecked = $item['varnishChecked'];
            $stainChecked = $item['stainChecked'];
            $oilChecked = $item['oilChecked'];
            $millChecked = $item['millChecked'];

            $basePricePerM2 = 0;
            switch ($woodType) {
              case "Jesion":
                $basePricePerM2 = ($thickness === 38) ? 6.00 : ($thickness === 27) ? 4.10 : 0;
                break;
              case "Dąb":
                $basePricePerM2 = ($thickness === 38) ? 6.50 : ($thickness === 27) ? 4.60 : ($thickness === 19) ? 3.20 : 0;
                break;
              case "Buk":
                $basePricePerM2 = ($thickness === 38) ? 5.10 : ($thickness === 27) ? 3.60 : ($thickness === 19) ? 2.50 : 0;
                break;
            }

            $area = $length * $width / 100;
            $priceForWider = 1;
            if ($width > 65) {
              $priceForWider *= 1.2;
            }

            $totalPricePerItem = $area * $basePricePerM2 * $priceForWider;

            $varnishPrice = 2.00; // 200zł za lakierowanie
            $stainPrice = 0.5; // 50zł za bejcowanie
            $oilPrice = 2.00; // 200zł za olejowanie
            $additionalPrice = 0;
            if ($varnishChecked) {
              $additionalPrice = $area * $varnishPrice;
            }
            if ($stainChecked) {
              $additionalPrice += $area * $stainPrice;
            }
            if ($oilChecked) {
              $additionalPrice = $area * $oilPrice;
            }
            if ($millChecked) {
              $additionalPrice += 10.00; // 10zł za frezowanie
            }

            $totalPricePerItem += $additionalPrice;
            $totalPricePerItem *= $piece;

            $totalPriceExcludingAllegro += $totalPricePerItem; // Dodanie ceny danego elementu do sumy

          ?>

          Cena poza Allegro: <?php echo $totalPricePerItem; ?> zł<br>
        </li>
      <?php endforeach

    <form id="orderForm" action="/controllers/submit_order.php" method="post">
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

        <label for="invoice_company_name">Nazwa firmy do faktury:</label>
        <input type="text" id="invoice_company_name" name="invoice_company_name"><br>

        <label for="invoice_address_street">Ulica do faktury:</label>
        <input type="text" id="invoice_address_street" name="invoice_address_street"><br>

        <label for="invoice_address_zipCode">Kod pocztowy do faktury:</label>
        <input type="text" id="invoice_address_zipCode" name="invoice_address_zipCode"><br>

        <label for="invoice_address_city">Miasto do faktury:</label>
        <input type="text" id="invoice_address_city" name="invoice_address_city"><br>

        <label for="comments">Dodatkowe uwagi:</label>
        <textarea id="comments" name="comments" rows="4"></textarea><br>

        <input type="submit" value="Złóż zamówienie">
    </form>
</div>