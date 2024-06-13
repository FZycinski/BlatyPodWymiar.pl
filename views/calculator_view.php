<?php include_once '../views/structure/header.php'; ?>
<title>Kalkulator cen blatów</title>
<style>
    .container {
        width: 70%;
        margin: 0 auto;
    }

    .calculatorFullForm {
        padding: 2px;
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .calculatorForm {
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
            '<input type="checkbox" id="varnish' + formIndex + '" value="varnish">' +
            '<label for="varnish' + formIndex + '">Lakierowanie</label>' +
            '</div>' +
            '<div class="checkbox-container">' +
            '<input type="checkbox" id="stain' + formIndex + '" value="stain">' +
            '<label for="stain' + formIndex + '">Bejcowanie</label>' +
            '</div>' +
            '<div class="checkbox-container">' +
            '<input type="checkbox" id="oil' + formIndex + '" value="oil">' +
            '<label for="oil' + formIndex + '">Olejowanie (twardym woskiem olejowym)</label>' +
            '</div>' +
            '<div id="requirementsMessage' + formIndex + '" class="requirements"></div>' +
            '<input type="checkbox" id="mill' + formIndex + '" value="mill"> Frezowanie (10-50 zł/blat)<br>' +
            '<div id="checkboxContainer"></div>' +
            '</form>' +
            '<div class="results"><p id="result1' + formIndex + '"></p>' +
            '<p id="result2' + formIndex + '"></p>' +
            '<p id="result3' + formIndex + '"></p>';

        var formsContainer = document.getElementById("formsContainer");
        formsContainer.appendChild(formContainer);
        document.getElementById("woodType" + formIndex).addEventListener("input", function() {
            calculatePrice(formIndex);
        });
        document.getElementById("thickness" + formIndex).addEventListener("input", function() {
            calculatePrice(formIndex);
        });
        document.getElementById("length" + formIndex).addEventListener("input", function() {
            var array = calculatePrice(formIndex);
            displayArrays(array);
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
        document.getElementById("newFormButton" + formIndex).addEventListener("click", function(event) {
            createNewForm();
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
        var totalPriceAllegro = Math.ceil(allegroPieces) * basePricePerM2;
        console.log(totalPriceAllegro);
        var priceWithDiscount = totalPrice * 0.9;
        if (!isNaN(totalPrice)) {
            document.getElementById("result1" + formIndex).innerText = "Cena Allegro: " + totalPriceAllegro.toFixed(2) + " zł.";
        }

        document.getElementById("result1" + formIndex).setAttribute('price_allegro1', totalPriceAllegro.toFixed(2));
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

        document.getElementById('summaryTotalAllegro').innerText = "Cena Allegro: " + totalPriceAl1.toFixed(2) + " zł.";
        document.getElementById('summaryTotalPrice').innerText = "Cena poza Allegro: " + totalPriceAl2.toFixed(2) + " zł.";
        document.getElementById('summaryDiscountedPrice').innerText = "Do kupienia przez Allegro : " + totalPriceAl3.toFixed(2) + " szt.";
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

        var varnishChecked = document.getElementById("varnish" + index).checked;
        var stainChecked = document.getElementById("stain" + index).checked;
        var oilChecked = document.getElementById("oil" + index).checked;
        var millChecked = document.getElementById("mill" + index).checked;

        formObj['varnishChecked'] = varnishChecked;
        formObj['stainChecked'] = stainChecked;
        formObj['oilChecked'] = oilChecked;
        formObj['millChecked'] = millChecked;

        var totalPriceAllegro = document.getElementById("result1" + index).getAttribute('price_allegro1');
        var totalPriceNonAllegro = document.getElementById("result2" + index).getAttribute('price_allegro2');
        var piecesForAllegro = document.getElementById("result3" + index).getAttribute('price_allegro3');

        formObj['totalPriceAllegro'] = parseFloat(totalPriceAllegro);
        formObj['totalPriceNonAllegro'] = parseFloat(totalPriceNonAllegro);
        formObj['piecesForAllegro'] = parseInt(piecesForAllegro);

        formsData.push(formObj);
    });

    document.getElementById('allFormData').value = JSON.stringify(formsData);
    document.getElementById('hiddenForm').submit();
}

</script>
</div>