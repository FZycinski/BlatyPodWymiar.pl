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
    <title>Calculated Prices</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Calculated Prices</h2>
    <table>
        <thead>
            <tr>
                <th>Wood Type</th>
                <th>Thickness</th>
                <th>Length</th>
                <th>Width</th>
                <th>Pieces</th>
                <th>Varnish</th>
                <th>Stain</th>
                <th>Oil</th>
                <th>Mill</th>
                <th>Price (Allegro)</th>
                <th>Price (Outside Allegro)</th>
                <th>Pieces (Allegro)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results['results'] as $result): ?>
                <tr>
                    <td><?php echo $result['woodType']; ?></td>
                    <td><?php echo $result['thickness']; ?></td>
                    <td><?php echo $result['length']; ?></td>
                    <td><?php echo $result['width']; ?></td>
                    <td><?php echo $result['piece']; ?></td>
                    <td><?php echo $result['varnish'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $result['stain'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $result['oil'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $result['mill'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $result['priceAllegro']; ?></td>
                    <td><?php echo $result['priceOutsideAllegro']; ?></td>
                    <td><?php echo $result['piecesAllegro']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Summary</h3>
    <p>Total Price (Allegro): <?php echo $results['summary']['totalAllegro']; ?></p>
    <p>Total Price (Outside Allegro): <?php echo $results['summary']['totalPrice']; ?></p>
    <p>Discounted Price: <?php echo $results['summary']['discountedPrice']; ?></p>
</body>
</html>
