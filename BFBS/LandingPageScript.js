//Function for product sliders and checking the sliders 
const productContainers = document.querySelectorAll('.product-container');
const nxtBtn = document.querySelectorAll('.nxt-btn');
const preBtn = document.querySelectorAll('.pre-btn');

const buffer = 10; 

productContainers.forEach((item, i) => {
    let containerDimensions = item.getBoundingClientRect();
    let containerWidth = containerDimensions.width;

    nxtBtn[i].addEventListener('click', () => {
        let maxScrollLeft = item.scrollWidth - containerWidth;
        let currentScrollLeft = item.scrollLeft;

        // Check if attempting to scroll beyond the last item
        if (maxScrollLeft - currentScrollLeft <= buffer) {
            alert("You're at the end of sliders.");
        } else {
            item.scrollLeft += containerWidth;
        }
    });

    preBtn[i].addEventListener('click', () => {
        let currentScrollLeft = item.scrollLeft;

        // Check if attempting to scroll before the first item
        if (currentScrollLeft <= buffer) {
            alert("You're at the beginning of sliders.");
        } else {
            item.scrollLeft -= containerWidth;
        }
    });
});



//Function for scrolling back to the top
window.addEventListener('scroll', function() {
    var backToTopButton = document.getElementById('backToTopBtn');
    if (window.pageYOffset > 300) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
});

// Smooth scroll function
function scrollToTop() {
    var currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
    if (currentScroll > 0) {
         window.requestAnimationFrame(scrollToTop);
         window.scrollTo(0, currentScroll - (currentScroll / 10));
    }
}

// Add event listener to the "Back to Top" button
document.getElementById('backToTopBtn').addEventListener('click', function() {
    scrollToTop();
});


document.addEventListener("DOMContentLoaded", function() {
    const aboutUsSection = document.querySelector(".aboutUs");
    const aboutUsTitle = document.querySelector(".aboutUs-title");
    const titleContainer = document.querySelector(".title-container");
    const line = document.querySelector(".line");
    
    const readMoreButtons = document.querySelectorAll(".read-more-btn");
    
    readMoreButtons.forEach(button => {
        button.addEventListener("click", function() {
            const parentContainer = this.closest('.aboutUs-container');
            const currentText = this.parentNode.querySelector('.read-more-text');
            const isTextVisible = currentText.classList.toggle('read-more-text--show');
            const isReadLess = this.textContent.includes('Less');
            
            if (!isTextVisible && !isReadLess) {
                this.textContent = "Read Less...";
            } else {
                this.textContent = isTextVisible ? "Read Less..." : "Read More...";
            }
            
            const readMoreTexts = parentContainer.querySelectorAll('.read-more-text');
            
            readMoreTexts.forEach(text => {
                if (text !== currentText && text.classList.contains('read-more-text--show')) {
                    text.classList.remove('read-more-text--show');
                }
            });
            
            // Calculate the new height of the aboutUs section
            const totalHeight = aboutUsTitle.clientHeight + titleContainer.clientHeight + line.clientHeight + parentContainer.scrollHeight;
            
            // Set the new height for the aboutUs section
            aboutUsSection.style.height = totalHeight + "px";
        });
    });
});


function showProductDetails(productId) {
    // You can make an AJAX request to fetch the details of the selected product
    // For simplicity, let's just display an alert with the product ID for now
    alert("Product ID: " + productId);
    // You can also load the details into the productDetails div using AJAX and update its content
}




