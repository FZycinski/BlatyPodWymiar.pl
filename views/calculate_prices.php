<?php include_once 'structure/header.php'; ?>
<!-- <title>Kalkulator cen blatów</title>
<div class="container">
    <div class="formsContainer" id="formsContainer"></div>
    <div class="summary" id="summary">
        Podsumowanie:<br><br>
        <span id="summaryTotalAllegro">Cena Allegro: </span>
        <span id="summaryTotalPrice">Cena poza Allegro: </span>
        <span id="summaryDiscountedPrice">Do kupienia przez Allegro: </span>
    </div>

    <button id="newFormButton">Dodaj blat</button>
    <button id="submitFormsButton">Prześlij zamówienie</button>

    <form id="hiddenForm" action="../controllers/CalculatorController.php" method="POST">
    <input type="hidden" id="allFormData" name="allFormData">
</form>

</div>
<script src="../resources/js/calculator_scripts.js"></script>
 -->

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Calculator</title>
</head>
<body>
    <h2>Price Calculator</h2>
    <form id="priceCalculatorForm">
        <label for="woodType">Wood Type:</label>
        <select name="woodType" id="woodType">
            <option value="Jesion">Jesion</option>
            <option value="Dąb">Dąb</option>
            <option value="Buk">Buk</option>
        </select><br><br>
        <label for="thickness">Thickness (mm):</label>
        <input type="number" name="thickness" id="thickness" min="1" step="1"><br><br>
        <label for="length">Length (cm):</label>
        <input type="number" name="length" id="length" min="1" step="1"><br><br>
        <label for="width">Width (cm):</label>
        <input type="number" name="width" id="width" min="1" step="1"><br><br>
        <label for="piece">Number of Pieces:</label>
        <input type="number" name="piece" id="piece" min="1" step="1"><br><br>
        <input type="checkbox" name="varnish" id="varnish">
        <label for="varnish">Varnish</label><br>
        <input type="checkbox" name="stain" id="stain">
        <label for="stain">Stain</label><br>
        <input type="checkbox" name="oil" id="oil">
        <label for="oil">Oil</label><br>
        <input type="checkbox" name="mill" id="mill">
        <label for="mill">Mill</label><br><br>
        <button type="button" onclick="calculatePrices()">Calculate Prices</button>
    </form>

    <div id="results"></div>

    <script>
        function calculatePrices() {
            var formData = {
                woodType: document.getElementById('woodType').value,
                thickness: document.getElementById('thickness').value,
                length: document.getElementById('length').value,
                width: document.getElementById('width').value,
                piece: document.getElementById('piece').value,
                varnish: document.getElementById('varnish').checked,
                stain: document.getElementById('stain').checked,
                oil: document.getElementById('oil').checked,
                mill: document.getElementById('mill').checked
            };

            fetch('CalculatorController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ allFormData: formData }),
            })
            .then(response => response.json())
            .then(data => {
                var resultsDiv = document.getElementById('results');
                resultsDiv.innerHTML = '<h3>Results:</h3>';
                data.results.forEach(result => {
                    resultsDiv.innerHTML += '<p>Price (Allegro): ' + result.priceAllegro + '</p>';
                    resultsDiv.innerHTML += '<p>Price (Outside Allegro): ' + result.priceOutsideAllegro + '</p>';
                    resultsDiv.innerHTML += '<p>Pieces (Allegro): ' + result.piecesAllegro + '</p>';
                });
                resultsDiv.innerHTML += '<h3>Summary:</h3>';
                resultsDiv.innerHTML += '<p>Total Price (Allegro): ' + data.summary.totalAllegro + '</p>';
                resultsDiv.innerHTML += '<p>Total Price (Outside Allegro): ' + data.summary.totalPrice + '</p>';
                resultsDiv.innerHTML += '<p>Discounted Price: ' + data.summary.discountedPrice + '</p>';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
