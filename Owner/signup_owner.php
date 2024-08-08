<?php
    session_start();
    include '../connect.php';
    include '../config.php';
    if(isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<style>
    #password-error, #email-error, #password-mismatch-error {
        display: none;
        color: red;
    }
</style>
<body>
<?php @include 'navbar.php' ?>
<div class="signupview">
    <div id="title">
        <h2>Create User</h2>
    </div>
    <form action="process_sign_up.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required onkeyup="validateEmail()"/><br><br>
        <div id="email-error">Invalid email format.</div>

        <label for="password">Password:</label>
        <input class="password" type="password" id="password" name="password" required onkeyup="validatePassword()"/><br><br>
        <div id="password-error">
            <p id="length-criteria" style="color: red;">Password must be at least 8 characters long.</p>
            <p id="uppercase-criteria" style="color: red;">Password must contain at least one uppercase letter.</p>
            <p id="lowercase-criteria" style="color: red;">Password must contain at least one lowercase letter.</p>
            <p id="digit-criteria" style="color: red;">Password must contain at least one digit.</p>
            <p id="special-char-criteria" style="color: red;">Password must contain at least one special character.</p>
        </div>

        <label for="confirmpassword">Confirm Password:</label>
        <input class="password" type="password" id="confirmpassword" name="confirmpassword" required onkeyup="validatePasswords()"/><br><br>
        <div id="password-mismatch-error">Passwords do not match.</div>

        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required /><br><br>
 
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required /><br><br>

        <label for="role">Role:</label>
        <select name="role" id="role"> 
            <option style="color:black" value="Chef">Chef</option>
            <option style="color:black" value="Cashier">Cashier</option>
            <option style="color:black" value="Inventory">Inventory</option>
            <option style="color:black" value="Admin">Admin</option>
            <option style="color:black" value="User">User</option>
        </select><br><br>

        <input type="Submit" name="stocksubmit" class="inputbutton" value="CONFIRM" />
        <br>
    </form>
</div>
</body>
</html>
<script>
    function validatePassword() {
        var password = document.getElementById("password").value;
        var passwordError = document.getElementById("password-error");
        var lengthCriteria = document.getElementById("length-criteria");
        var uppercaseCriteria = document.getElementById("uppercase-criteria");
        var lowercaseCriteria = document.getElementById("lowercase-criteria");
        var digitCriteria = document.getElementById("digit-criteria");
        var specialCharCriteria = document.getElementById("special-char-criteria");

        passwordError.style.display = password ? "block" : "none";

        // Criteria regex
        var lengthRegex = /.{8,}/;
        var uppercaseRegex = /[A-Z]/;
        var lowercaseRegex = /[a-z]/;
        var digitRegex = /\d/;
        var specialCharRegex = /[\W_]/;

        // Length validation
        lengthCriteria.style.color = lengthRegex.test(password) ? "green" : "red";
        // Uppercase letter validation
        uppercaseCriteria.style.color = uppercaseRegex.test(password) ? "green" : "red";
        // Lowercase letter validation
        lowercaseCriteria.style.color = lowercaseRegex.test(password) ? "green" : "red";
        // Digit validation
        digitCriteria.style.color = digitRegex.test(password) ? "green" : "red";
        // Special character validation
        specialCharCriteria.style.color = specialCharRegex.test(password) ? "green" : "red";
    }

    function validateEmail() {
        var email = document.getElementById("email").value;
        var emailError = document.getElementById("email-error");
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        emailError.style.display = email && !emailRegex.test(email) ? "block" : "none";
    }

    function validatePasswords() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmpassword").value;
        var passwordMismatchError = document.getElementById("password-mismatch-error");

        // Check if passwords match
        passwordMismatchError.style.display = password !== confirmPassword ? "block" : "none";
    }

    function validateForm() {
        var lengthCriteria = document.getElementById("length-criteria");
        var uppercaseCriteria = document.getElementById("uppercase-criteria");
        var lowercaseCriteria = document.getElementById("lowercase-criteria");
        var digitCriteria = document.getElementById("digit-criteria");
        var specialCharCriteria = document.getElementById("special-char-criteria");
        var emailError = document.getElementById("email-error");
        var passwordMismatchError = document.getElementById("password-mismatch-error");

        // Ensure all criteria are met and passwords match
        return (
            lengthCriteria.style.color === "green" &&
            uppercaseCriteria.style.color === "green" &&
            lowercaseCriteria.style.color === "green" &&
            digitCriteria.style.color === "green" &&
            specialCharCriteria.style.color === "green" &&
            emailError.style.display === "none" &&
            passwordMismatchError.style.display === "none"
        );
    }
</script>
<?php
        }
    }
    else {
        header("Location: ../home.php");
        exit();
    }
?>
