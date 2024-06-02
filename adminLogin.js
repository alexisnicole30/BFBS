 // Sample login data
 var sampleLoginData = {
    username: "admin",
    password: "password123"
};

document.querySelector(".Adminbtn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent form from submitting normally

    var enteredUsername = document.querySelector(".Admin-input-field input[type='text']").value;
    var enteredPassword = document.querySelector(".Admin-input-field input[type='password']").value;

    if (enteredUsername === sampleLoginData.username && enteredPassword === sampleLoginData.password) {
        // Successful login
        document.getElementById("errorMessage").style.display = "none"; // Hide the error message
        window.location.href = "./admin/home.php"; // Redirect to admin page
    } else {
        // Failed login
        document.getElementById("errorMessage").textContent = "Wrong username or password!"; // Display the error message
        document.getElementById("errorMessage").style.display = "flex"; // Show the error message

        // Hide the error message after 2 seconds
        setTimeout(function() {
            document.getElementById("errorMessage").style.display = "none";
        }, 2000);
    }
});