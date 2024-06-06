<?php include_once 'structure/header.php'; ?>
<title>Kalkulator cen blatów</title>
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

    <form id="hiddenForm" action="/calculator/handleRequest" method="POST">
    <input type="hidden" id="allFormData" name="allFormData">
</form>

</div>
<script src="../resources/js/calculator_scripts.js"></script>
<?php include_once 'structure/footer.php'; ?>