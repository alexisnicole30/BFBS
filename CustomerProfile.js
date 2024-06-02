document.addEventListener("DOMContentLoaded", function() {
    var userIcon = document.getElementById("user");
    var userOverlay = document.getElementById("userOverlay");

    userIcon.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent the default behavior of the link
        event.stopPropagation(); // Prevent click event from bubbling up to document

        // Toggle the display of the overlay
        if (userOverlay.style.display === "block") {
            userOverlay.style.display = "none";
        } else {
            userOverlay.style.display = "block";
        }
    });

    // Close the overlay when clicking outside of it
    document.addEventListener("click", function(event) {
        if (!userIcon.contains(event.target) && userOverlay.style.display === "block") {
            userOverlay.style.display = "none";
        }
    });
});

document.getElementById('prof-btn').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('change-profile').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
});

document.getElementById('logout').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default link behavior
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'Logout.php';
        }
    });
});
/*
document.getElementById("save-btn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent form submission

    // For demonstration purposes, update the profile info and show the modal
    var firstName = document.getElementById("First-name").value;
    var lastName = document.getElementById("Last-name").value;
    var username = firstName + lastName;

    document.getElementById("profile-username").innerText = username;
    document.getElementById("profile-picture").src = "./images/profile.png";

    document.getElementById("myModal").style.display = "block";

    
});*/

/*
// Load stored values on page load
window.onload = function() {
    var storedFirstName = localStorage.getItem("firstName");
    var storedLastName = localStorage.getItem("lastName");
    var storedEmail = localStorage.getItem("email");
    var storedPhoneNumber = localStorage.getItem("phoneNumber");
    var storedGenderId = localStorage.getItem("gender");
    var storedDateOfBirth = localStorage.getItem("dateOfBirth");
    var storedProfilePicture = localStorage.getItem("profilePicture");

    // Display stored values in the form fields and profile picture
    document.getElementById("First-name").value = storedFirstName || "";
    document.getElementById("Last-name").value = storedLastName || "";
    document.getElementById("Customer-email").value = storedEmail || "";
    document.getElementById("Customer-Pnumber").value = storedPhoneNumber || "";
    document.getElementById("date-of-birth").value = storedDateOfBirth || "";

    // Set selected radio button based on stored gender ID
    if (storedGenderId) {
        document.getElementById(storedGenderId).checked = true;
    }

    // Display stored profile picture or default profile picture
    var profilePicture = document.getElementById("profile-picture");
    var profilePicture1 = document.getElementById("profile-picture1");
    var changeProfile = document.getElementById("change-profile");
    var defaultUsername = "Guest";
    var defaultProfilePicture = "./images/profile.png";
    if (storedProfilePicture) {
        profilePicture.src = storedProfilePicture;
        profilePicture1.src = storedProfilePicture;
        changeProfile.src = storedProfilePicture;
    } else {
        profilePicture.src = defaultProfilePicture;
        profilePicture1.src = "./images/profile.png";
        changeProfile.src = "./images/profile.png";
    }
    */
    /*
    // Display stored values in the greeting and profile username
    var username = storedFirstName ? (storedFirstName + " " + storedLastName) : defaultUsername;
    document.getElementById("profile-username").innerText = username;*/

  /*  
};*/

/*
document.getElementById("save-btn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent form submission

    // Check if any of the input fields are empty
    var firstName = document.getElementById("First-name").value.trim();
    var lastName = document.getElementById("Last-name").value.trim();
    var email = document.getElementById("Customer-email").value.trim();
    var phoneNumber = document.getElementById("Customer-Pnumber").value.trim();
    var gender = document.querySelector('input[name="Customer-gender"]:checked');
    var dateOfBirth = document.getElementById("date-of-birth").value;

    if (firstName === '' || lastName === '' || email === '' || phoneNumber === '' || gender === null || dateOfBirth === '') {
        // Display error message in the modal
        document.getElementById("errorModal").style.display = "block";
        // Reload the page after a short delay
        setTimeout(function() {
            window.location.reload();
        }, 3000);
        document.getElementById("myModal").style.display = "none";
    } else {
        // If all fields are filled, continue with saving data
        var username = firstName + " " + lastName;

        // Update stored values
        localStorage.setItem("firstName", firstName);
        localStorage.setItem("lastName", lastName);
        localStorage.setItem("email", email);
        localStorage.setItem("phoneNumber", phoneNumber);
        if (gender) {
            localStorage.setItem("gender", gender.id); // Store the ID of the selected radio button
        }
        localStorage.setItem("dateOfBirth", dateOfBirth);

        // Update greeting message
        document.getElementById("greeting").innerText = "Hi, " + firstName + "!";

        // Update profile username
        document.getElementById("profile-username").innerText = username;

        // Update profile picture
        var fileInput = document.getElementById("prof-btn");
        var file = fileInput.files[0]; // Get the selected file
        var profilePicture = document.getElementById("profile-picture");
        var profilePicture1 = document.getElementById("profile-picture1");
        var changeProfile = document.getElementById("change-profile");

        if (file) {

            if (file.size > 1024 * 1024) { // Check if file size is greater than 1 MB
                alert("Please select an image file smaller than 1 MB.");
                document.getElementById("myModal").style.display = "none";
                
                return;
            } else if (!(/\.(png|jpeg)$/i).test(file.name)) { // Check if file is not .png or .jpeg
                alert("Unsupported file type selected. Please select a .png or .jpeg file.");
                document.getElementById("myModal").style.display = "none";
                localStorage.clear();
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                profilePicture.src = e.target.result; // Set the image source to the selected file
                localStorage.setItem("profilePicture", e.target.result); // Store the profile picture in local storage
            };
            reader.readAsDataURL(file);
        } else if (!localStorage.getItem("profilePicture")) {
            // If no file is selected and no profile picture is stored, display the default profile picture
            profilePicture.src = "./images/profile.png";
            profilePicture1.src = "./images/profile.png";
            changeProfile.src = "./images/profile.png";
            localStorage.setItem("profilePicture", "./images/profile.png"); // Store the default profile picture in local storage
        }

        // Display success message modal
        document.getElementById("myModal").style.display = "block";
        // Reload the page after a short delay
        setTimeout(function() {
            window.location.reload();
        }, 3000);
    }
});*/

// Close the modal when the close button is clicked
document.getElementsByClassName("close")[0].addEventListener("click", function() {
    document.getElementById("myModal").style.display = "none";
    // Reload the page after a short delay
    setTimeout(function() {
        window.location.reload();
    }, 3000);
});

// Close the modal when the user clicks outside of it
window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("myModal")) {
        document.getElementById("myModal").style.display = "none";
        // Reload the page after a short delay
        setTimeout(function() {
            window.location.reload();
        }, 3000);    
    }
});

// Close the error modal when the close button is clicked
document.getElementsByClassName("close-error")[0].addEventListener("click", function() {
    document.getElementById("errorModal").style.display = "none";
    // Reload the page after a short delay
    setTimeout(function() {
        window.location.reload();
    }, 3000);
});

// Close the error modal when the user clicks outside of it
window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("errorModal")) {
        document.getElementById("errorModal").style.display = "none";
        // Reload the page after a short delay
        setTimeout(function() {
            window.location.reload();
        }, 3000);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Function to display the section based on the URL hash or default to profile section
    function displaySectionFromHash() {
        var hash = window.location.hash;
        var activeSection = document.querySelector(hash);
        var links = document.querySelectorAll('.subMenu-MyAccount a');

        // Remove 'active' class from all links
        links.forEach(function(link) {
            link.classList.remove('active');
        });

        // Add 'active' class to the link that corresponds to the active section
        var activeLink = document.querySelector(`.subMenu-MyAccount a[href$="${hash}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }

        // Display the active section
        if (activeSection) {
            activeSection.style.display = 'block';
        }
    }

    // Call the function on initial load if there is a hash in the URL
    if(window.location.hash) {
        displaySectionFromHash();
    } else {
        // If there is no hash, display the profile section and set it as active by default
        document.querySelector('.rightSide-container').style.display = 'block';
        document.querySelector('.subMenu-MyAccount a[href$="#rightSide-container"]').classList.add('active');
    }

    // Add event listener for hash changes
    window.addEventListener('hashchange', displaySectionFromHash);
});




function toggleDisplay(tag, element) {
    var containers = document.querySelectorAll('.rightSide-container, .addresses-container, .change-password-container, .myPurchases-container');
    var links = document.querySelectorAll('.subMenu-MyAccount a');
    
    // Remove 'active' class from all links
    links.forEach(function(link) {
        link.classList.remove('active');
    });
    
    // Add 'active' class to the clicked link
    element.classList.add('active');
    
    // Hide all containers
    containers.forEach(function(container) {
        container.style.display = 'none';
    });
    
    // Create a mapping of tags to corresponding container class names
    var tagToContainer = {
        'profile': '.rightSide-container',
        'addresses': '.addresses-container',
        'change-password': '.change-password-container',
        'myPurchases': '.myPurchases-container',
        'purchase-label': '.myPurchases-container' // Handle 'purchase-label' tag
    };
    
    // Display the corresponding container based on the clicked link
    if (tagToContainer[tag]) {
        document.querySelector(tagToContainer[tag]).style.display = 'block';
    }
}

//new added

document.getElementById("editBtn").addEventListener("click", function() {
    // Change form action attribute to point to the update script
    document.querySelector('.unique-address-form form').action = 'update_address.php';
});



/*
// Load address data when the page loads
window.addEventListener('load', function() {
    var savedAddressData = localStorage.getItem('addressData');
    if (savedAddressData) {
        var addressData = JSON.parse(savedAddressData);
        populateAddressFields(addressData);
        hideAddAddressButton(); // Check if address data exists and hide the button
    }
});*/

/*
// Function to populate address fields
function populateAddressFields(addressData) {
    document.getElementById('fullname').textContent = addressData.fullName;
    document.getElementById('PhoneNumber').textContent = addressData.phoneNumber;
    document.getElementById('street').textContent = addressData.streetName;
    document.getElementById('purok1').textContent = addressData.purok;
    document.getElementById('barangay').textContent = addressData.barangay;
    document.getElementById('barangay2').textContent = addressData.barangay;
    document.getElementById('city').textContent = addressData.city;
    document.getElementById('province1').textContent = addressData.province;

    var defaultStatus = document.querySelector('.default-status');
    if (addressData.isDefault === 'yes') {
        defaultStatus.style.display = 'flex';
    } else {
        defaultStatus.style.display = 'none';
    }

    // Show the updated address container
    var updatedAddressContainer = document.querySelector('.updated-address-container');
    updatedAddressContainer.style.display = 'block';
}

// Address form functions
function openOverlay() {
    document.getElementById("overlay").style.display = "flex";
}

function closeOverlay() {
    document.getElementById("overlay").style.display = "none";
}

function saveAndUpdate() {
    var fullName = document.getElementById('form-fullname').value;
    var phoneNumber = document.getElementById('form-PhoneNumber').value;
    var streetName = document.getElementById('streetName').value;
    var purok = document.getElementById('purok').value;
    var barangay = document.getElementById('form-barangay').value;
    var city = document.getElementById('form-city').value;
    var province = document.getElementById('province').value;
    var isDefault = document.querySelector('input[name="setDefault"]:checked').value;

    // Save address data to localStorage
    var addressData = {
        fullName: fullName,
        phoneNumber: phoneNumber,
        streetName: streetName,
        purok: purok,
        barangay: barangay,
        city: city,
        province: province,
        isDefault: isDefault
    };

    localStorage.setItem('addressData', JSON.stringify(addressData));

    // Display success message
    alert('Address successfully updated.');

    // Reload the page
    window.location.reload();
}
*/
/*
// Function to hide the "ADD NEW ADDRESS" button and its associated container
function hideAddAddressButton() {
    var addAddressButton = document.getElementById('add-address');
    var addAddressesContainer = document.querySelector('.addAddresses-container');
    if (addAddressButton && addAddressesContainer) {
        addAddressButton.style.display = 'none';
        addAddressesContainer.style.display = 'none';
    }
}

// Function to handle edit button click
function handleEditButtonClick() {
    var editButton = document.getElementById('edit-btn');
    if (editButton) {
        editButton.addEventListener('click', function() {
            // Get the address data from localStorage
            var savedAddressData = localStorage.getItem('addressData');
            if (savedAddressData) {
                var addressData = JSON.parse(savedAddressData);
                // Populate the form fields with the saved address data
                document.getElementById('form-fullname').value = addressData.fullName;
                document.getElementById('form-PhoneNumber').value = addressData.phoneNumber;
                document.getElementById('streetName').value = addressData.streetName;
                document.getElementById('purok').value = addressData.purok;
                document.getElementById('form-barangay').value = addressData.barangay;
                document.getElementById('form-city').value = addressData.city;
                document.getElementById('province').value = addressData.province;
                // Show the overlay for editing
                openOverlay();
            } else {
                alert('No address data found.');
            }
        });
    }
}

// Call the function to handle edit button click
handleEditButtonClick();
*/
/*
// Function to handle delete button click
function handleDeleteButtonClick() {
    var deleteButton = document.getElementById('delete-btn');
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            // Confirm with the user before deleting the address
            var confirmDelete = confirm("Are you sure you want to delete this address?");
            if (confirmDelete) {
                // Remove address data from localStorage
                localStorage.removeItem('addressData');
                // Hide the updated address container
                var updatedAddressContainer = document.querySelector('.updated-address-container');
                updatedAddressContainer.style.display = 'none';
                // Show the add new address button
                var addAddressButton = document.getElementById('add-address');
                if (addAddressButton) {
                    addAddressButton.style.display = 'block';
                }
                // Display success message
                alert('Address successfully deleted.');
                // Reload the page
                window.location.reload();
            }
        });
    }
}

// Call the function to handle delete button click
handleDeleteButtonClick();
*/
/*
// Function to validate the form before submitting
// Function to validate the form before submitting
function validateForm() {
    var fullName = document.getElementById('form-fullname').value;
    var phoneNumber = document.getElementById('form-PhoneNumber').value;
    var streetName = document.getElementById('streetName').value;
    var purok = document.getElementById('purok').value;
    var barangay = document.getElementById('form-barangay').value;
    var city = document.getElementById('form-city').value;
    var province = document.getElementById('province').value;
    var isDefault = document.querySelector('input[name="setDefault"]:checked');

    // Regular expression pattern for Philippine cellular numbers
    var phonePattern = /^(09|\+639)\d{9}$/;

    // Check if any required fields are empty
    if (!fullName.trim()) {
        alert('The Full Name field is required.');
        return false;
    }
    if (!phoneNumber.trim()) {
        alert('The Phone Number field is required.');
        return false;
    }
    if (!phonePattern.test(phoneNumber)) {
        alert('Please enter a valid Philippine cellular number.');
        return false;
    }
    if (!streetName.trim()) {
        alert('The Street field is required.');
        return false;
    }
    if (purok === "") {
        alert('The Purok field is required.');
        return false;
    }
    if (barangay === "") {
        alert('The Barangay field is required.');
        return false;
    }
    if (city === "") {
        alert('The City field is required.');
        return false;
    }
    if (province === "") {
        alert('The Province field is required.');
        return false;
    }
    if (!isDefault) {
        alert('Please select whether to set as default or not.');
        return false;
    }

    return true;
}
*/
// Add event listener to the form submit button
document.querySelector('.submit-btn').addEventListener('click', function(event) {
    // Validate the form before submitting
    if (!validateForm()) {
        // Prevent form submission if validation fails
        event.preventDefault();
    }
});



/*New Password functionalities */
/*
document.addEventListener('DOMContentLoaded', function() {
    var newPasswordInput = document.getElementById('new-password-input');
    var confirmPasswordInput = document.getElementById('confirm-password-input');
    var saveButton = document.getElementById('newPassword-save-btn');
    var modal = document.getElementById('myModal1');
    var modalContent = document.querySelector('.modal-content1');
    var modalMessage = document.getElementById('modal-message');
    var closeBtn = document.querySelector('.close1');

    saveButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent form submission

        var newPassword = newPasswordInput.value;
        var confirmPassword = confirmPasswordInput.value;

        // Password validation criteria
        var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;

        if (!strongRegex.test(newPassword)) {
            showModal('<i class="fas fa-exclamation-triangle"></i>', 'Password Requirements', 'Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character.');
            return;
        }

        if (newPassword !== confirmPassword) {
            showModal('<i class="fas fa-exclamation-triangle"></i>', 'Password Mismatch', 'Passwords do not match. Please try again.');
        } else {
            showModal('<i class="fas fa-check-circle"></i>', 'Success', 'Password Change Successfully!');
        }
    });

    function showModal(icon, title, message) {
        modalMessage.innerHTML = '<div style="text-align: center;">' + icon + '<h2>' + title + '</h2><p>' + message + '</p></div>';
        modal.style.display = 'block';
    }

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
//Password strength indication
document.addEventListener('DOMContentLoaded', function() {
    var newPasswordInput = document.getElementById('new-password-input');
    var passwordStrengthIndicator = document.getElementById('password-strength');

    newPasswordInput.addEventListener('input', function() {
        var password = newPasswordInput.value.trim(); // Trim leading and trailing whitespace

        if (password === '') {
            passwordStrengthIndicator.textContent = ''; // Clear the password strength indicator
            return; // Exit the function if password is empty
        }

        var strength = calculatePasswordStrength(password);

        if (strength === 'weak') {
            passwordStrengthIndicator.style.color = 'red';
            passwordStrengthIndicator.textContent = 'Weak Password (at least 8 chars,special chars and numbers)';
        } else if (strength === 'strong') {
            passwordStrengthIndicator.style.color = 'green';
            passwordStrengthIndicator.textContent = 'Strong Password';
        } else {
            passwordStrengthIndicator.style.color = ''; // Reset color
            passwordStrengthIndicator.textContent = ''; // Reset content
        }
    });

    function calculatePasswordStrength(password) {
        // Implement password strength criteria
        var hasUppercase = /[A-Z]/.test(password);
        var hasLowercase = /[a-z]/.test(password);
        var hasNumber = /\d/.test(password);
        var hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password);

        if (password.length < 8 || !hasUppercase || !hasLowercase || !hasNumber || !hasSpecialChar) {
            return 'weak';
        } else {
            return 'strong';
        }
    }
});

*/
/*
document.getElementById('currentOrder-btn').addEventListener('click', function() {
    showOrderSection('currentOrder');
});

document.getElementById('completed-btn').addEventListener('click', function() {
    showOrderSection('completedOrder');
});

document.getElementById('canceled-btn').addEventListener('click', function() {
    showOrderSection('canceledOrder');
});*/

function showOrderSection(sectionId) {
    // Hide all sections
    var orderSections = document.getElementsByClassName('orders-info');
    for (var i = 0; i < orderSections.length; i++) {
        orderSections[i].classList.remove('active');
    }

    // Show the selected section
    document.getElementById(sectionId).classList.add('active');
}
//new
function showOrderContainer(status) {
    // Remove active class from all buttons except the clicked one
    document.querySelectorAll(".orderBtn").forEach(btn => btn.classList.remove("active"));
    // Add active class to the clicked button
    document.querySelector(`.orderBtn[data-status="${status}"]`).classList.add("active");

    // Hide all order containers
    document.querySelectorAll(".order-container").forEach(container => {
        container.style.display = "none";
    });

    // Show the corresponding order container
    const containerId = `${status}Container`;
    document.getElementById(containerId).style.display = "block";

    // Ensure that the "Current Order" container is shown when its button is clicked
    if (status === "current") {
        document.getElementById("currentOrderContainer").style.display = "block";
    }
}

// Initialize by showing the "Current Order" container
showOrderContainer("current");





/*
// Initialize by showing the current order section
showOrderSection('currentOrder');

*/
/*My Purchase Functionalities*/
/*
document.addEventListener("DOMContentLoaded", function() {
    const currentOrderBtn = document.getElementById("currentOrder-btn");
    const completedBtn = document.getElementById("completed-btn");
    const canceledBtn = document.getElementById("canceled-btn");

    const ordersContainer = document.getElementById("orders-container");
    const completedOrderContainer = document.getElementById("completed-order-container");
    const canceledOrderContainer = document.getElementById("canceled-order-container");

    // Function to set initial state
    function setInitialState() {
        ordersContainer.style.display = "block";
        completedOrderContainer.style.display = "none";
        canceledOrderContainer.style.display = "none";
        currentOrderBtn.style.borderBottom = "3px solid #de007a";
        completedBtn.style.borderBottom = "none";
        canceledBtn.style.borderBottom = "none";
    }

    // Call the function to set initial state
    setInitialState();

    // Event listener for Current Order button
    currentOrderBtn.addEventListener("click", function() {
        ordersContainer.style.display = "block";
        completedOrderContainer.style.display = "none";
        canceledOrderContainer.style.display = "none";
        currentOrderBtn.style.borderBottom = "3px solid #de007a";
        completedBtn.style.borderBottom = "none";
        canceledBtn.style.borderBottom = "none";
    });

    // Event listener for Completed button
    completedBtn.addEventListener("click", function() {
        ordersContainer.style.display = "none";
        completedOrderContainer.style.display = "block";
        canceledOrderContainer.style.display = "none";
        currentOrderBtn.style.borderBottom = "none";
        completedBtn.style.borderBottom = "3px solid #de007a";
        canceledBtn.style.borderBottom = "none";
    });

    // Event listener for Canceled button
    canceledBtn.addEventListener("click", function() {
        ordersContainer.style.display = "none";
        completedOrderContainer.style.display = "none";
        canceledOrderContainer.style.display = "block";
        currentOrderBtn.style.borderBottom = "none";
        completedBtn.style.borderBottom = "none";
        canceledBtn.style.borderBottom = "3px solid #de007a";
    });
});
*/

/*Sample Review Rating*/ 
// Get the modal element
var modal = document.getElementById("myNewModal");

// Get the button that opens the modal
var btn = document.getElementById("rate-btn");

// Get the <span> element that closes the modal
var span = document.getElementById("close-icon");

// When the user clicks on the button, open the modal
btn.onclick = function() {
    // Replace these with the actual product name, quantity ordered, order total, star rating, order number, and date purchased
    var productName = 'Love Like No Other';
    var quantityOrdered = 'x1';
    var orderTotal = '₱2,799';
    var starRating = 4; // This should be an integer between 1 and 5
    var orderNumber = '20240001';
    var datePurchased = '04/08/2024';
    var feedback = 'This product is amazing! It exceeded all my expectations and I would definitely recommend it to others.';
    
    document.getElementById('product-name-review').textContent = productName;
    document.getElementById('quantity').textContent = quantityOrdered;
    
    // Clear existing content and set order total
    var orderTotalElement = document.getElementById('order-total-review');
    orderTotalElement.textContent = 'Order Total: ' + orderTotal;
    
    document.getElementById('order-number-review').textContent = 'Order ID: ' + orderNumber;
    document.getElementById('date-purchased-review').textContent = datePurchased;
    
    var rateStars = document.querySelector('.rate-star');
    rateStars.innerHTML = '';
    for (var i = 0; i < starRating; i++) {
        var star = document.createElement('i');
        star.className = 'fas fa-star';
        rateStars.appendChild(star);
    }
    
    document.getElementById('review').textContent = feedback;
    
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

//Cancellation Details Overlay functionalities
const viewCancelBtn = document.getElementById('view-cancel-btn');
const cancelDetailsContainer = document.querySelector('.cancellationDetails-container');
const closeBtn = document.querySelector('.cancel-close-btn');

viewCancelBtn.addEventListener('click', function() {
    cancelDetailsContainer.classList.add('show-overlay');
});

closeBtn.addEventListener('click', function() {
    cancelDetailsContainer.classList.remove('show-overlay');
});
