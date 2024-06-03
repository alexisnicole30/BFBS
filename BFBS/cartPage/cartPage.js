/*
document.addEventListener('DOMContentLoaded', function() {
    var dropdownIcon = document.getElementById('dropdown-icon');
    var variationOptions = document.getElementById('variation-options');
    var confirmSelectionBtn;

    dropdownIcon.addEventListener('click', function() {
        if (variationOptions.style.display === 'none' || variationOptions.style.display === '') {
            variationOptions.style.display = 'block';
            dropdownIcon.classList.remove('fa-caret-down');
            dropdownIcon.classList.add('fa-caret-up');
            // Apply smooth transition
            variationOptions.style.transition = 'display 0.3s ease-in-out';

            // Create the confirm selection button if it doesn't exist
            if (!confirmSelectionBtn) {
                confirmSelectionBtn = document.createElement('button');
                confirmSelectionBtn.setAttribute('id', 'confirm-selection-btn');
                confirmSelectionBtn.setAttribute('class', 'confirm-selection-btn');
                confirmSelectionBtn.textContent = 'Confirm Selection';
                variationOptions.appendChild(confirmSelectionBtn);

                // Add event listener to the confirm selection button
                confirmSelectionBtn.addEventListener('click', function() {
                    updateSelection();
                });
            }
        } else {
            variationOptions.style.display = 'none';
            dropdownIcon.classList.remove('fa-caret-up');
            dropdownIcon.classList.add('fa-caret-down');

            // Remove the confirm selection button if it exists
            if (confirmSelectionBtn) {
                variationOptions.removeChild(confirmSelectionBtn);
                confirmSelectionBtn = null;
            }
        }
    });

    // Handle selection of variations
    var flowerSelect = document.getElementById('flower-select');
    var wrapperSelect = document.getElementById('wrapper-select');
    var ribbonSelect = document.getElementById('ribbon-select');
    var gourmetSelect = document.getElementById('gourmet-select');
    var addOnSelect = document.getElementById('addOn-select');

    var selectedFlower = '';
    var selectedWrapper = '';
    var selectedRibbon = '';
    var selectedGourmet = '';
    var selectedAddOn = '';

    flowerSelect.addEventListener('change', function() {
        selectedFlower = flowerSelect.value;
    });

    wrapperSelect.addEventListener('change', function() {
        selectedWrapper = wrapperSelect.value;
    });

    ribbonSelect.addEventListener('change', function() {
        selectedRibbon = ribbonSelect.value;
    });

    gourmetSelect.addEventListener('change', function() {
        selectedGourmet = gourmetSelect.value;
    });

    addOnSelect.addEventListener('change', function() {
        selectedAddOn = addOnSelect.value;
    });

    function updateSelection() {
        if (selectedFlower !== '' && selectedWrapper !== '' && selectedRibbon !== '' && selectedGourmet !== '' && selectedAddOn !== '') {
            document.getElementById('selectedFlower').innerText = "Flower: " + selectedFlower;
            document.getElementById('selectedWrapper').innerText = "Wrapper: " + selectedWrapper;
            document.getElementById('selectedRibbon').innerText = "Ribbon: " + selectedRibbon;
            document.getElementById('selectedGourmet').innerText = "Gourmet Delight: " + selectedGourmet;
            document.getElementById('selectedAddOn').innerText = "Add-Ons: " + selectedAddOn;
        } else {
            alert('Please select all fields.');
        }
    }

    // Close variation options when clicking outside
    document.addEventListener('click', function(event) {
        if (!variationOptions.contains(event.target) && event.target !== dropdownIcon) {
            variationOptions.style.display = 'none';
            dropdownIcon.classList.remove('fa-caret-up');
            dropdownIcon.classList.add('fa-caret-down');

            // Remove the confirm selection button if it exists
            if (confirmSelectionBtn) {
                variationOptions.removeChild(confirmSelectionBtn);
                confirmSelectionBtn = null;
            }
        }
    });

});
*/






//For quantity functionality total and remove
/*
document.addEventListener("DOMContentLoaded", function() {
    const decrementButton = document.getElementById("decrement");
    const incrementButton = document.getElementById("increment");
    const quantityDisplay = document.getElementById("quantity");
    const originalPrice = 1499; // Original price
    const discountedPrice = 899; // Discounted price
    const cartProdTotal = document.getElementById("cartProdTotal");
    const removeButton = document.getElementById("cartRemove-label");
    const productCardContainer = document.querySelector(".cart-product-cardContainer");

    decrementButton.addEventListener("click", function() {
      let currentQuantity = parseInt(quantityDisplay.textContent);
      if (currentQuantity > 1) {
        quantityDisplay.textContent = currentQuantity - 1;
        updateTotal();
      }
    });

    incrementButton.addEventListener("click", function() {
      let currentQuantity = parseInt(quantityDisplay.textContent);
      quantityDisplay.textContent = currentQuantity + 1;
      updateTotal();
    });

    /*
    removeButton.addEventListener("click", function() {
      // Show confirmation dialog
      if (confirm("Are you sure you want to remove this product?")) {
        // Remove the product card
        productCardContainer.remove();
      }
    });
    /*

    function updateTotal() {
      let currentQuantity = parseInt(quantityDisplay.textContent);
      let totalPrice = currentQuantity * discountedPrice;
      cartProdTotal.textContent = "₱" + totalPrice.toLocaleString('en-US', {minimumFractionDigits: 0});
    }
  });*/


  /*
var checkOutBtn = document.getElementById("checkOut-btn");

checkOutBtn.addEventListener('click', function(){

    window.location.href = "./Chkoutprogressbar.html";
});*/
  