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
        '<input type="checkbox" id="varnish' + formIndex + '" name="varnish" value="varnish">' +
        '<label for="varnish' + formIndex + '">Lakierowanie</label>' +
        '</div>' +
        '<div class="checkbox-container">' +
        '<input type="checkbox" id="stain' + formIndex + '" name="stain" value="stain">' +
        '<label for="stain' + formIndex + '">Bejcowanie</label>' +
        '</div>' +
        '<div class="checkbox-container">' +
        '<input type="checkbox" id="oil' + formIndex + '" name="oil" value="oil">' +
        '<label for="oil' + formIndex + '">Olejowanie</label>' +
        '</div>' +
        '<div id="requirementsMessage' + formIndex + '" class="requirements"></div>' +
        '<input type="checkbox" id="mill' + formIndex + '" name="mill" value="mill"> Frezowanie (10-15 PLN/m2)' +
        '</form>' +
        '<p id="result1' + formIndex + '"></p>' +
        '<p id="result2' + formIndex + '"></p>' +
        '<p id="result3' + formIndex + '"></p>';

    var formsContainer = document.getElementById("formsContainer");
    formsContainer.appendChild(formContainer);

    document.getElementById("varnish" + formIndex).addEventListener("input", function() {
        updateCheckboxState(formIndex);
    });
    document.getElementById("stain" + formIndex).addEventListener("input", function() {
        updateCheckboxState(formIndex);
    });
    document.getElementById("oil" + formIndex).addEventListener("input", function() {
        updateCheckboxState(formIndex);
    });
    document.getElementById("mill" + formIndex).addEventListener("input", function() {
        createCheckbox(formIndex);
    });
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
    var forms = document.querySelectorAll('[id^="priceCalculatorForm"]');
    forms.forEach(function(form, index) {
        var formData = {
            woodType: document.getElementById("woodType" + index).value,
            thickness: document.getElementById("thickness" + index).value,
            length: document.getElementById("length" + index).value,
            width: document.getElementById("width" + index).value,
            piece: document.getElementById("piece" + index).value,
            varnish: document.getElementById("varnish" + index).checked,
            stain: document.getElementById("stain" + index).checked,
            oil: document.getElementById("oil" + index).checked,
            mill: document.getElementById("mill" + index).checked,
        };
        formsData.push(formData);
    });

    var hiddenForm = document.getElementById("hiddenForm");
    var allFormDataInput = document.getElementById("allFormData");
    allFormDataInput.value = JSON.stringify(formsData);
    console.log("Submitting formsData: ", formsData);
    console.log("Hidden form data: ", allFormDataInput.value);
    hiddenForm.submit();
}

    forms.forEach(function(form, index) {
        var formData = new FormData(form);
        var formObj = {};
        formData.forEach(function(value, key) {
            formObj[key] = value;
        });
        formsData.push(formObj);
    });

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php?action=calculate", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            updateResults(response);
        }
    };

    xhr.send("allFormData=" + JSON.stringify(formsData));


function updateResults(response) {
    response.results.forEach(function(result, index) {
        document.getElementById("result1" + index).innerText = "Cena Allegro: " + result.priceAllegro.toFixed(2) + " zł.";
        document.getElementById("result2" + index).innerText = "Cena poza Allegro: " + result.priceOutsideAllegro.toFixed(2) + " zł.";
        document.getElementById("result3" + index).innerText = "Do kupienia przez Allegro: " + result.piecesAllegro + " szt.";
    });

    document.getElementById('summaryTotalAllegro').innerText = "Cena Allegro: " + response.summary.totalAllegro.toFixed(2) + " zł.";
    document.getElementById('summaryTotalPrice').innerText = "Cena poza Allegro: " + response.summary.totalPrice.toFixed(2) + " zł.";
    document.getElementById('summaryDiscountedPrice').innerText = "Do kupienia przez Allegro: " + response.summary.discountedPrice.toFixed(2) + " szt.";
}

