const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
const sign_in_btn2 = document.querySelector("#sign-in-btn2");
const sign_up_btn2 = document.querySelector("#sign-up-btn2");
sign_up_btn.addEventListener("click", () => {
    container.classList.add("sign-up-mode");
});
sign_in_btn.addEventListener("click", () => {
    container.classList.remove("sign-up-mode");
});
sign_up_btn2.addEventListener("click", () => {
    container.classList.add("sign-up-mode2");
});
sign_in_btn2.addEventListener("click", () => {
    container.classList.remove("sign-up-mode2");
});

//validation

function validateForm() {
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var username = document.getElementById("username").value;

    var emailWarning = document.getElementById("emailWarning");
    var passwordWarning = document.getElementById("passwordWarning");
    var usernameWarning = document.getElementById("usernameWarning");

    // Reset previous warnings
    emailWarning.textContent = "";
    passwordWarning.textContent = "";
    usernameWarning.textContent = "";

    // Basic email validation
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        emailWarning.textContent = "*Invalid email address";
        return false;
    }

    // Password length validation
    if (password.length < 8) {
        passwordWarning.textContent = "*Password must be at least 8 characters long";
        return false;
    }

    // Password strength validation
    var passwordPattern = /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[@#$%^&+=]).*$/;
    if (!passwordPattern.test(password)) {
        passwordWarning.textContent = "*Password must contain at least one number, letter, and special character";
        return false;
    }

    // Username validation
    var usernamePattern = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]*$/;
    if (username.length < 5 || !usernamePattern.test(username)) {
        usernameWarning.textContent = "*Username must be at least 5 characters long and contain both letters and numbers";
        return false;
    }

    // Assuming the form is valid, show the success message
    var overlayContainer = document.getElementById("overlay-container");
    overlayContainer.style.display = "flex"; // Show the overlay

    // Clear input fields
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("username").value = "";

    // Hide the overlay after 5 seconds and redirect to login.html
    setTimeout(function() {
        overlayContainer.style.display = "none"; // Hide the overlay
        window.location.href = '/html/login.html'; // Redirect to login.html
    }, 2000); // 5000 milliseconds = 5 seconds

    return false; // Prevent form submission for demonstration
}


// Sample login data
var sampleLoginData = {
    username: "user",
    password: "1234"
};

document.getElementById("loginBtn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var enteredUsername = document.querySelector(".input-field input[type='text']").value;
    var enteredPassword = document.querySelector(".input-field input[type='password']").value;

    // Check if username and password match sample login data
    if (enteredUsername === sampleLoginData.username && enteredPassword === sampleLoginData.password) {
        // Successful login
        window.location.href = "../Client_HTML/what_s_new.html"; 
    } else {
        // Display error message
        var errorMessage = document.getElementById("errorMessage");
        errorMessage.textContent = "Invalid username or password";

        // Set a timeout to hide the error message after 2 seconds
        setTimeout(function() {
            errorMessage.textContent = ""; // Clear the error message
        }, 2000);
    }
});
