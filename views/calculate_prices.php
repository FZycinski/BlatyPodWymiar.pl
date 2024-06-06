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

<?php include_once '../views/structure/header.php'; ?>
<title>Kalkulator cen blatów</title>
<style>
    .container {
        width: 70%;
        margin: 0 auto;
    }

    .calculatorFullForm {
        border: 1px solid black;
        padding: 2px;
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .calculatorForm {
        border: 1px solid black;
        padding: 2px;
        flex: 1;
        margin-right: 10px;
        border-radius: 5px;
    }

    .results {
        padding: 5px;
        flex: 1;
    }

    .results p {
        margin: 0;
    }

    #formsContainer {
        display: flex;
        flex-direction: column;
    }

    #newFormButton {
        margin: 0 auto;
        display: block;
        padding: 10px 20px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #newFormButton:hover {
        background-color: #0056b3;
    }

    #summary {
        text-align: center;
        font-size: 18px;
    }
</style>
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

    <form id="hiddenForm" action="order_form_view.php" method="post" style="display: none;">
        <input type="hidden" id="allFormData" name="allFormData">
    </form>
</div>

    <script>
document.getElementById("newFormButton").addEventListener("click", function(event) {
    createNewForm();
});

document.getElementById("submitFormsButton").addEventListener("click", function(event) {
    submitForms();
});

var newFormButton = document.getElementById("newFormButton");

window.onload = function() {
    newFormButton.click();
};

function createNewForm() {
    var formIndex = document.querySelectorAll('[id^="priceCalculatorForm"]').length;
    var formContainer = document.createElement("div");
    formContainer.classList.add("calculatorFullForm");
    formContainer.innerHTML = '<form class="calculatorForm" id="priceCalculatorForm' + formIndex + '">' +
        '<select class ="selector" id="woodType' + formIndex + '" name="woodType">' +
        '<option value="Jesion">Jesion</option>' +
        '<option value="Dąb">Dąb</option>' +
        '<option value="Buk">Buk</option>' +
        '</select>' +
        '<select class ="selector" id="thickness' + formIndex + '" name="thickness">' +
        '<option value="38">38 mm</option>' +
        '<option value="27">27 mm</option>' +
        '<option value="19">19 mm</option>' +
        '</select><br>' +
        'Długość (cm): <input class ="input" type="number" id="length' + formIndex + '" name="length" required><br>' +
        'Szerokość (cm): <input class ="input"type="number" id="width' + formIndex + '" name="width" required><br>' +
        'Liczba sztuk: <input class ="input" type="number" id="piece' + formIndex + '" name="piece" value=1 required><br>' +
        '<div class="checkbox-container">' +
        '<input type="checkbox" id="varnish' + formIndex + '" name="varnish">' +
        '<label for="varnish' + formIndex + '">Lakierowanie</label>' +
        '</div>' +
        '<div class="checkbox-container">' +
        '<input type="checkbox" id="stain' + formIndex + '" name="stain">' +
        '<label for="stain' + formIndex + '">Bejcowanie</label>' +
        '</div>' +
        '<div class="checkbox-container">' +
        '<input type="checkbox" id="oil' + formIndex + '" name="oil">' +
        '<label for="oil' + formIndex + '">Olejowanie</label>' +
        '</div>' +
        '<div id="requirementsMessage' + formIndex + '" class="requirements"></div>' +
        '<input type="checkbox" id="mill' + formIndex + '" name="mill"> Frezowanie (10-50 zł/blat)<br>' +
        '</form>' +
        '<div class="results"><p id="result1' + formIndex + '"></p>' +
        '<p id="result2' + formIndex + '"></p>' +
        '<p id="result3' + formIndex + '"></p>';

    var formsContainer = document.getElementById("formsContainer");
    formsContainer.appendChild(formContainer);
}

function submitForms() {
    var formsData = [];
    var forms = document.querySelectorAll('.calculatorForm');

    forms.forEach(function(form) {
        var formData = new FormData(form);
        var formObj = {};
        formData.forEach(function(value, key) {
            formObj[key] = value;
        });
        formsData.push(formObj);
    });

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "path_to_your_controller/calculatorcontroller.php?action=handleRequest", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            displayResults(response);
        }
    };
    xhr.send("allFormData=" + JSON.stringify(formsData));
}

function displayResults(response) {
    var results = response.results;
    results.forEach(function(result, index) {
        document.getElementById("result1" + index).innerText = "Cena Allegro: " + result.priceAllegro.toFixed(2) + " zł.";
        document.getElementById("result2" + index).innerText = "Cena poza Allegro: " + result.priceOutsideAllegro.toFixed(2) + " zł.";
        document.getElementById("result3" + index).innerText = "Do kupienia przez Allegro: " + result.piecesAllegro + " szt.";
    });

    var summary = response.summary;
    document.getElementById('summaryTotalAllegro').innerText = "Cena Allegro: " + summary.totalAllegro.toFixed(2) + " zł.";
    document.getElementById('summaryTotalPrice').innerText = "Cena poza Allegro: " + summary.totalPrice.toFixed(2) + " zł.";
    document.getElementById('summaryDiscountedPrice').innerText = "Do kupienia przez Allegro: " + summary.discountedPrice + " szt.";
}

    function updateCheckboxState(formIndex) {
        var varnishChecked = document.getElementById("varnish" + formIndex).checked;
        var stainChecked = document.getElementById("stain" + formIndex).checked;
        var oilChecked = document.getElementById("oil" + formIndex).checked;

        var requirementsMessage = document.getElementById("requirementsMessage" + formIndex);
        requirementsMessage.textContent = "";

        if (stainChecked && !varnishChecked) {
            requirementsMessage.textContent = "Bejcowanie wymaga lakierowania.";
            document.getElementById("varnish" + formIndex).checked = true; // Odznacz checkbox varnish
        } else if (oilChecked && (stainChecked || varnishChecked)) {
            requirementsMessage.textContent += " Nie można używać oleju z bejcą ani lakierem.";
            document.getElementById("varnish" + formIndex).checked = false; // Odznacz checkbox varnish
            document.getElementById("stain" + formIndex).checked = false; // Odznacz checkbox stain
        }
    }

    function submitForms() {
        var formsData = [];
        var forms = document.querySelectorAll('.calculatorForm');

        forms.forEach(function(form, index) {
            var formData = new FormData(form);
            var formObj = {};
            formData.forEach(function(value, key) {
                formObj[key] = value;
            });
            formsData.push(formObj);
        });

        document.getElementById('allFormData').value = JSON.stringify(formsData);
        document.getElementById('hiddenForm').submit();
    }
</script>
</div>
<?php include_once 'structure/footer.php'; ?>