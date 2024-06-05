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

var newFormButton = document.getElementById("newFormButton");

window.onload = function() {
    newFormButton.click();
};

function createNewForm() {
    var formIndex = document.querySelectorAll('[id^="priceCalculatorForm"]').length;
    var formContainer = document.createElement("div");
    formContainer.classList.add("calculatorFullForm");
    formContainer.innerHTML = '<form class="calculatorForm" id="priceCalculatorForm' + formIndex + '">' +
        '<select class="selector" id="woodType' + formIndex + '" name="woodType">' +
        '<option value="Jesion">Jesion</option>' +
        '<option value="Dąb">Dąb</option>' +
        '<option value="Buk">Buk</option>' +
        '</select>' +
        '<select class="selector" id="thickness' + formIndex + '" name="thickness">' +
        '<option value="38">38 mm</option>' +
        '<option value="27">27 mm</option>' +
        '<option value="19">19 mm</option>' +
        '</select><br>' +
        'Długość (cm): <input class="input" type="number" id="length' + formIndex + '" name="length" required><br>' +
        'Szerokość (cm): <input class="input" type="number" id="width' + formIndex + '" name="width" required><br>' +
        'Liczba sztuk: <input class="input" type="number" id="piece' + formIndex + '" name="piece" value="1" required><br>' +
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
        '<div id="checkboxContainer"></div>' +
        '</form>' +
        '<div class="results"><p id="result1' + formIndex + '"></p>' +
        '<p id="result2' + formIndex + '"></p>' +
        '<p id="result3' + formIndex + '"></p></div>';

    var formsContainer = document.getElementById("formsContainer");
    formsContainer.appendChild(formContainer);
    document.getElementById("woodType" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("thickness" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("length" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("width" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("piece" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("varnish" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
    });
    document.getElementById("stain" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
        updateCheckboxState(formIndex);
    });
    document.getElementById("oil" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
        updateCheckboxState(formIndex);
    });
    document.getElementById("mill" + formIndex).addEventListener("input", function() {
        calculatePrice(formIndex);
        updateCheckboxState(formIndex);
        createCheckbox(formIndex);
    });
}

function calculatePrice(formIndex) {
    var woodType = document.getElementById("woodType" + formIndex).value;
    var thickness = parseFloat(document.getElementById("thickness" + formIndex).value);
    var length = parseFloat(document.getElementById("length" + formIndex).value);
    var width = parseFloat(document.getElementById("width" + formIndex).value);
    var piece = parseInt(document.getElementById("piece" + formIndex).value);
    var varnish = document.getElementById("varnish" + formIndex).checked;
    var stain = document.getElementById("stain" + formIndex).checked;
    var oil = document.getElementById("oil" + formIndex).checked;
    var mill = document.getElementById("mill" + formIndex).checked;

    var basePricePerM2;
    switch (woodType) {
        case "Jesion":
            basePricePerM2 = (thickness === 38) ? 6.00 : (thickness === 27) ? 4.10 : 0;
            break;
        case "Dąb":
            basePricePerM2 = (thickness === 38) ? 6.50 : (thickness === 27) ? 4.60 : (thickness === 19) ? 3.20 : 0;
            break;
        case "Buk":
            basePricePerM2 = (thickness === 38) ? 5.10 : (thickness === 27) ? 3.60 : (thickness === 19) ? 2.50 : 0;
            break;
        default:
            basePricePerM2 = 0;
    }

    var area = length * width / 100;
    var priceForWider = 1;
    if (width > 65) {
        priceForWider *= 1.2;
    }
    var totalPrice = area * basePricePerM2 * priceForWider;

    var varnishPrice = 2.00; //200zł za lakierowanie
    var stainPrice = 0.5; //50zł za bejcowanie
    var oilPrice = 2.00; // 200zł za olejowanie
    var additionalPrice = 0;
    if (varnish) {
        additionalPrice = area * varnishPrice;
    }
    if (stain) {
        additionalPrice = additionalPrice + area * stainPrice;
    }
    if (oil) {
        additionalPrice = area * oilPrice;
    }
    if (mill) {
        additionalPrice += 10.00; // 10zł za frezowanie
    }
    totalPrice += additionalPrice;
    totalPrice *= piece;
    var allegroPieces = totalPrice / basePricePerM2;
    var priceWithDiscount = totalPrice * 0.9;
    if (!isNaN(totalPrice)) {
        document.getElementById("result1" + formIndex).innerText = "Cena Allegro: " + totalPrice.toFixed(2) + " zł.";
    }

    document.getElementById("result1" + formIndex).setAttribute('price_allegro1', totalPrice.toFixed(2));
    if (!isNaN(totalPrice)) {
        document.getElementById("result2" + formIndex).innerText = "Cena poza Allegro: " + priceWithDiscount.toFixed(2) + " zł.";
    }
    document.getElementById("result2" + formIndex).setAttribute('price_allegro2', priceWithDiscount.toFixed(2));
    if (!isNaN(totalPrice)) {
        document.getElementById("result3" + formIndex).innerText = "Do kupienia przez Allegro: " + Math.ceil(allegroPieces) + " szt.";
    }
    document.getElementById("result3" + formIndex).setAttribute('price_allegro3', Math.ceil(allegroPieces));


    const elementsWithPrice1 = document.querySelectorAll('[price_allegro1]');
    let totalPriceAl1 = 0;
    elementsWithPrice1.forEach(element => {
        let price = parseFloat(element.getAttribute('price_allegro1'));
        if (!isNaN(price)) {
            totalPriceAl1 += price;
        }
    });
    const elementsWithPrice2 = document.querySelectorAll('[price_allegro2]');
    let totalPriceAl2 = 0;
    elementsWithPrice2.forEach(element => {
        let price = parseFloat(element.getAttribute('price_allegro2'));
        if (!isNaN(price)) {
            totalPriceAl2 += price;
        }
    });
    const elementsWithPrice3 = document.querySelectorAll('[price_allegro3]');
    let totalPriceAl3 = 0;
    elementsWithPrice3.forEach(element => {
        let price = parseFloat(element.getAttribute('price_allegro3'));
        if (!isNaN(price)) {
            totalPriceAl3 += price;
        }
    });

    const overallPriceEl1 = document.getElementById('overallPrice1');
    if (overallPriceEl1) {
        overallPriceEl1.innerText = 'Całkowita cena Allegro: ' + totalPriceAl1.toFixed(2) + ' zł.';
    }
    const overallPriceEl2 = document.getElementById('overallPrice2');
    if (overallPriceEl2) {
        overallPriceEl2.innerText = 'Całkowita cena poza Allegro: ' + totalPriceAl2.toFixed(2) + ' zł.';
    }
    const overallPriceEl3 = document.getElementById('overallPrice3');
    if (overallPriceEl3) {
        overallPriceEl3.innerText = 'Do kupienia przez Allegro: ' + Math.ceil(totalPriceAl3) + ' szt.';
    }
}


function updateCheckboxState(formIndex) {
    const checkboxes = document.querySelectorAll(`#priceCalculatorForm${formIndex} input[type="checkbox"]`);
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            this.value = this.checked ? 'Tak' : 'Nie';
        });
    });
}

function createCheckbox(formIndex) {
    var checkboxContainer = document.getElementById("checkboxContainer");
    checkboxContainer.innerHTML = '<label><input type="checkbox" name="extraCheckbox"> Frezowanie</label>';
}

</script>
</div>