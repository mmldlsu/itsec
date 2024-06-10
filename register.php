<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Owner/style.css">
  </head>
  <body>
    <div class="loginwrapper">
      <div class="logincard">
        <form action="register_handler.php" method="post" enctype="multipart/form-data">
          <h1>Register</h1>

          <?php if(isset($_GET['error'])) { ?>
            <p class='error'><?php echo $_GET['error']; ?></p>
          <?php } ?>

          <div class="textfield">
            <input type="text" name="full_name" placeholder="Full Name" required /><br>
            <input type="email" name="email" placeholder="Email" required /><br>
            <input type="password" name="password" placeholder="Password" required /><br>
            <input type="file" name="profile_picture" accept="image/*" /><br>
          </div>

          <button type="submit" class="loginsubmitbutton" name="registerBtn">REGISTER</button>

          <button type="button" class="registerbutton" onclick="location.href='index.php'">BACK TO LOGIN</button>
        </form>
      </div>
    </div>
  </body>
</html>
