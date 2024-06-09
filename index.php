<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Owner/style.css">
  </head>
  <body>
    <div class="loginwrapper">
      <div class="logincard">
        <form action="login.php" method="post">
          <h1>Restoran</h1>

          <?php if(isset($_GET['error'])) { ?>
            <p class='error'><?php echo $_GET['error']; ?></p>
          <?php } ?>

          <div class="textfield">
            <input type="text" name="username" placeholder="Username" required /><br>
            <input type="password" name="password" placeholder="Password" required /><br>
          </div>

          <button type="submit" class="loginsubmitbutton" name="loginBtn">LOGIN</button>

          <button type="button" class="registerbutton" onclick="location.href='register.php'">REGISTER</button>
        </form>
      </div>
    </div>
  </body>
</html>
