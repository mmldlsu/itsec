<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Owner/style.css">
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

    function validateForm() {
        var lengthCriteria = document.getElementById("length-criteria");
        var uppercaseCriteria = document.getElementById("uppercase-criteria");
        var lowercaseCriteria = document.getElementById("lowercase-criteria");
        var digitCriteria = document.getElementById("digit-criteria");
        var specialCharCriteria = document.getElementById("special-char-criteria");

        return (
            lengthCriteria.style.color === "green" &&
            uppercaseCriteria.style.color === "green" &&
            lowercaseCriteria.style.color === "green" &&
            digitCriteria.style.color === "green" &&
            specialCharCriteria.style.color === "green"
        );
    }
    </script>
    <style>
    .textfield {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        text-align: start;
        color: #55868C;
    }

    .textfield input[type="file"] {
        grid-column: span 2;
    }

    .loginsubmitbutton,
    .registerbutton {
        margin-top: 20px;
        display: block;
        width: 100%;
    }

    #password-error {
        display: none;
    }
    </style>
</head>
<body>
    <div class="loginwrapper">
        <div class="logincard">
            <form action="register_handler.php" method="post" enctype="multipart/form-data"
                onsubmit="return validateForm()">
                <h1>Register</h1>

                <?php if (isset($_GET['error'])) { ?>
                <p class='error'><?php echo htmlspecialchars($_GET['error']); ?></p>
                <?php } ?>

                <div class="textfield">
                    <input type="text" name="firstname" placeholder="Firstname" required />
                    <input type="text" name="lastname" placeholder="Lastname" required />
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" id="password" name="password" placeholder="Password" required
                        onkeyup="validatePassword()" />
                    <div id="password-error">
                        <p id="length-criteria" style="color: red;">Password must be at least 8 characters long.</p>
                        <p id="uppercase-criteria" style="color: red;">Password must contain at least one uppercase letter.</p>
                        <p id="lowercase-criteria" style="color: red;">Password must contain at least one lowercase letter.</p>
                        <p id="digit-criteria" style="color: red;">Password must contain at least one digit.</p>
                        <p id="special-char-criteria" style="color: red;">Password must contain at least one special character.</p>
                    </div>
                    <input type="file" name="profile_picture" accept="image/*" />
                </div>

                <button type="submit" class="loginsubmitbutton" name="registerBtn">REGISTER</button>

                <button type="button" class="registerbutton" onclick="location.href='index.php'">BACK TO LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
