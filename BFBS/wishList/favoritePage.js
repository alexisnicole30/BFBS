/*

const removeButtons = document.querySelectorAll('.fav-remove-btn');

removeButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Ask for confirmation
        const isConfirmed = confirm('Are you sure you want to remove this item from your wishlist?');
        
        // If confirmed, remove the product card
        if (isConfirmed) {
            const productCard = button.closest('.fav-product-cardContainer');
            productCard.remove();
            // Check if there are any products left in the wishlist
            const wishlistItems = document.querySelectorAll('.fav-product-cardContainer');
            if (wishlistItems.length === 0) {
                document.querySelector('.fav-initial-status').style.display = 'flex';
            }
        }
    });
});

// Function to show or hide the initial status message based on the number of wishlist items
function toggleInitialStatus() {
    const wishlistItems = document.querySelectorAll('.fav-product-cardContainer');
    if (wishlistItems.length === 0) {
        document.querySelector('.fav-initial-status').style.display = 'flex';
    } else {
        document.querySelector('.fav-initial-status').style.display = 'none';
    }
}


window.onload = function() {
    toggleInitialStatus();
};


var favBuyNowBtn = document.getElementById('fav-BuyNow-btn');
var favAddCartBtn = document.getElementById('fav-AddCart-btn');

favBuyNowBtn.addEventListener('click', function(){
    window.location.href = "../Client_HTML/Buy_now.html";
});

favAddCartBtn.addEventListener('click', function(){
    window.location.href = "../cartPage.html";
});
*/

