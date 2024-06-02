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
        setTimeout(function() {
            emailWarning.textContent = "";
        }, 2000);
        return false;
    }

    // Password length validation
    if (password.length < 8) {
        passwordWarning.textContent = "*Password must be at least 8 characters long";
        setTimeout(function() {
            passwordWarning.textContent = "";
        }, 2000);
        return false;
    }

    // Password strength validation
    var passwordPattern = /^(?=.*\d)(?=.*[a-zA-Z])(?=.*[@#$%^&+=]).*$/;
    if (!passwordPattern.test(password)) {
        passwordWarning.textContent = "*Password must contain at least one number, letter, and special character";
        setTimeout(function() {
            passwordWarning.textContent = "";
        }, 2000);
        return false;
    }

    // Username validation
    var usernamePattern = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]*$/;
    if (username.length < 5 || !usernamePattern.test(username)) {
        usernameWarning.textContent = "*Username must be at least 5 characters long and contain both letters and numbers";
        setTimeout(function() {
            usernameWarning.textContent = ""; 
        }, 2000);
        return false;
    }

   
    var overlayContainer = document.getElementById("overlay-container");
    overlayContainer.style.display = "flex"; // Show the overlay

    // Clear input fields
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("username").value = "";

    
    setTimeout(function() {
        overlayContainer.style.display = "none"; 
        window.location.href = '/html/login.html';
    }, 2000); 

    return false; 
}


/*
//sample window for customer login
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('.sign-in-form');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault(); 
       
        window.location.href = 'customer.html';
    });
});*/
/*
$(document).ready(function() {
    $('#register').submit(function(event) {
        event.preventDefault();

        let form = $('#register')[0];
        let formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: 'register.php', 
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Username already exists. Please choose a different username.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Registered successfully',
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error: ' + response.error,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'AJAX Error: ' + error,
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});*/