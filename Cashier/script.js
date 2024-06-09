function chooseDish(dishName, img, dishPrice) {
    var parentElement = document.getElementById('loop-invoice');
    var newElement = document.createElement('div');

    newElement.innerHTML =  
        '<div class="media mb-2">'                                                                                                                                                              +
            '<img src="../Chef/' + img +'" class="align-self-center mr-3" width="95">'                                                                                                    +
            '<div class="media-body">'                                                                                                                                                          +
                '<h6 class="mt-0" id="dish_' + dishName + '">' + dishName + '</h6>'                                                                                                                                        +
                '<p>Php <span id="price_' + dishName + '" value="' + dishPrice + '">' + dishPrice + '</span></p>'                                                                               +
            '</div>'                                                                                                                                                                            +
            '<input class="quantity mt-3" id="qty' + dishName + '" type="number" value="1" onchange="updateField(\'' + dishName + '\', \'' + dishPrice + '\')" min="0">' +
            '<button class="btn delete mt-2" onclick="removeDish(this, \'' + dishName + '\')"><img src="https://img.icons8.com/wired/35/000000/trash.png"></button>'                            +
        '</div>';

    parentElement.appendChild(newElement);

    var button = document.getElementById("btn" + dishName);
    button.disabled = true;

    calculateTotal();
}
function updateField(dishName, dishPrice) {
    var inputValue = document.getElementById("qty" + dishName).value;
    document.getElementById("price_" + dishName).textContent  = inputValue * dishPrice;

    calculateTotal();
}
function removeDish(button, dishName) {
    var dishElement = button.parentNode;
    dishElement.parentNode.removeChild(dishElement);

    var button = document.getElementById("btn" + dishName);
    button.disabled = false;

    calculateTotal();
}
function removeAll() {
    var divElement = document.getElementById("loop-invoice");
    divElement.innerHTML = "";

    var priceSpans = document.querySelectorAll("[id^='btn']");
    priceSpans.forEach(function(span) { total += span.textContent; });

    var buttons = document.querySelectorAll('[id^="btn"]');
    buttons.forEach(function(button) { button.disabled = false; });

    calculateTotal();
}
function calculateTotal() {
    var totalSpan = document.getElementById("total");
    var priceSpans = document.querySelectorAll("[id^='price_']");
    
    var total = 0;
    priceSpans.forEach(function(span) { total += parseInt(span.textContent); });

    totalSpan.textContent = total;
    appendChosenDish();
}
function validateForm(event) {
    var moneyInput = document.getElementById("money");
    var totalSpan = document.getElementById("total");
    var moneyValue = moneyInput.value;
    var totalValue = parseFloat(totalSpan.textContent);
  
    if (moneyValue < totalValue || moneyValue === "") {
      event.preventDefault(); // Prevent form submission
      alert("Please enter an amount equal to or greater than the total.");
    }
}
function appendChosenDish() {
    var form = document.getElementById("pay");
    var qtyInputs = document.querySelectorAll('h6[id^="dish_"]');
  
    // Remove existing hidden inputs
    var hiddenInputs = form.querySelectorAll('input[type="hidden"]');
    hiddenInputs.forEach(function(hiddenInput) {
      form.removeChild(hiddenInput);
    });
  
    // Append new hidden inputs
    qtyInputs.forEach(function(input) {
      var hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "vDish[]";
      hiddenInput.value = input.textContent;
      form.appendChild(hiddenInput);
    });

    appendChosenDishQuantity();
}
function appendChosenDishQuantity() {
    var form = document.getElementById("pay");
    var qtyInputs = document.querySelectorAll('input[id^="qty"]');

    // Append new hidden inputs
    qtyInputs.forEach(function(input) {
      var hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "vQuantity[]";
      hiddenInput.value = input.value;
      form.appendChild(hiddenInput);
    });
}

document.getElementById("pay").addEventListener("submit", validateForm);