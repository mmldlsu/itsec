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
      var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

      if (!regex.test(password)) {
        passwordError.innerHTML = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        passwordError.style.color = "red";
        return false;
      }
      passwordError.innerHTML = "";
      return true;
    }

    function validateForm() {
      return validatePassword();
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
    </style>
</head>
<body>
  <div class="loginwrapper">
    <div class="logincard">
      <form action="register_handler.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <h1>Register</h1>

        <?php if (isset($_GET['error'])) { ?>
          <p class='error'><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>

        <div class="textfield">
          <input type="text" name="firstname" placeholder="Firstname" required />
          <input type="text" name="lastname" placeholder="Lastname" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" id="password" name="password" placeholder="Password" required onkeyup="validatePassword()" />
          <span id="password-error"></span>
          <input type="file" name="profile_picture" accept="image/*" />
        </div>

        <button type="submit" class="loginsubmitbutton" name="registerBtn">REGISTER</button>

        <button type="button" class="registerbutton" onclick="location.href='index.php'">BACK TO LOGIN</button>
      </form>
    </div>
  </div>
</body>
</html>
