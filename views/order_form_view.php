<div>
        Złóż zamówienie
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