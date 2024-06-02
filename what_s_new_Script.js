let slideContainer = document.querySelector('.mySlide');
let slides = document.querySelectorAll('.slide');

function nextSlide() {
    let lastSlide = slides[slides.length - 1];
    slideContainer.insertBefore(lastSlide, slides[0]);
    slides = document.querySelectorAll('.slide'); // Update the slides NodeList
}

function prevSlide() {
    let firstSlide = slides[0];
    slideContainer.appendChild(firstSlide);
    slides = document.querySelectorAll('.slide'); // Update the slides NodeList
}

// Add event listeners to the next and previous buttons
document.querySelector('.next').addEventListener('click', nextSlide);
document.querySelector('.prev').addEventListener('click', prevSlide);


// dara ang script sa search bar

function openOverlay() {
    var overlay = document.getElementById("overlay");
    overlay.style.display = "block"; // Show the overlay
}




function closeOverlay() {
    var overlay = document.getElementById("overlay");
    overlay.style.display = "none"; // Hide the overlay
}


function toggleSelection(button) {
    button.classList.toggle("selected");
}



function showFilter() {
    var filter = document.getElementById("filter-overlay");
    filter.style.display = "flex";
}


const filterPopup = document.getElementById('filter-overlay');
const modal = document.getElementById('overlay');
const notification = document.getElementById('notification-overlay');

window.onclick = function(event) {
    if (event.target === filterPopup) {
        filterPopup.style.display = "none";
    }
    if (event.target === modal) {
        modal.style.display = "none";
    }
    if (event.target === notification) {
        notification.style.display = "none";
    }
}



let notificationCount = 0;

// Function to update notification count and display
function updateNotificationCount() {
    const notificationCountElement = document.getElementById('notification-count');
    notificationCountElement.textContent = notificationCount;
    notificationCountElement.style.display = notificationCount > 0 ? 'inline-block' : 'none';
}

// Function to add a new notification
function addNotification(message) {
    const notificationList = document.getElementById('notification-list');
    const notificationItem = document.createElement('li');
    notificationItem.textContent = message;
    notificationList.appendChild(notificationItem);
    notificationCount++;
    updateNotificationCount();
}


document.getElementById('close-notification-overlay').addEventListener('click', function() {
    document.getElementById('notification-overlay').style.display = 'none';
});

document.getElementById('read-all').addEventListener('click', function() {
    const notificationItems = document.querySelectorAll('#notification-list li');
    notificationItems.forEach(function(item) {
        item.classList.add('read');
    });
});

document.getElementById('delete-all').addEventListener('click', function() {
    const notificationList = document.getElementById('notification-list');
    notificationList.innerHTML = '';
    notificationCount = 0;
    updateNotificationCount();
});


function openNotification() {
    var notification = document.getElementById("notification-overlay");
    notification.style.display = "flex";
}









// function resetForm() {
//
//     var buttons = document.querySelectorAll(".filter-btn");
//     buttons.forEach(function(btn) {
//         btn.classList.remove("selected");
//     });
//
//     document.getElementById("minPrice").value = "";
//     document.getElementById("maxPrice").value = "";
// }

// function filter() {
//     var minPrice = document.getElementById("minPrice").value;
//     var maxPrice = document.getElementById("maxPrice").value;
//     var selectedButtons = document.querySelectorAll(".selected");
//
//     var selectedCategories = [];
//     selectedButtons.forEach(function(btn) {
//         selectedCategories.push(btn.innerText);
//     });
//
//     var slides = document.querySelectorAll(".slide");
//     slides.forEach(function(slide) {
//         var price = slide.querySelector(".price").innerText;
//         var category = slide.querySelector(".category").innerText;
//
//         if (price >= minPrice && price <= maxPrice && selectedCategories.includes(category)) {
//             slide.style.display = "block";
//         } else {
//             slide.style.display = "none";
//         }
//     });
// }
//
// function resetFilter() {
//     var slides = document.querySelectorAll(".slide");
//     slides.forEach(function(slide) {
//         slide.style.display = "block";
//     });
// }