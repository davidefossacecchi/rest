<?php
  session_start();
  if(!isset($_SESSION["user"])){
    session_unset();
    session_destroy();
    header("Location: http://".$_SERVER['HTTP_HOST']);
  }
  else $user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LoginApp</title>
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <body>
    <div class="container">
      <h2>yourProfile</h2>
      <form>
        <span id="message" class="error">Incorrect username and/or password</span>
        <input type="email" name="email" placeholder="email" value="<?php echo $user["username"]; ?>" required>
        <input type="password" name="password" placeholder="password" required>
        <input type="password" name="password_conf" placeholder="repeat password" required>
        <button type="submit">Edit</button>
      </form>
      <p style="text-align:left;">
        <ul>
          <li id="delete"><a href="">Delete...</a></li>
          <li id="logout"><a href="logout.php">Logout...</a></li>
        </ul>
      </p>
    </div>
    <script src="js/jquery-2.1.3.min.js"></script>
    <script>
      var user_id = <?php echo $user["id"]; ?>;
      var rest_path = "rest.php/"+user_id;
    </script>
    <script src="js/manage.js"></script>
  </body>
</html>