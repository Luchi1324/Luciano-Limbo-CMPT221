<!--
Front-ends limbo-admin.php with a login page.
Made by Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/custom.css">
  </head>

<body>
 <!-- Menu Bars -->
 <div class = 'header'>
    <span class = 'titles'>
      <a href="limbo.php"><button type="submit">Home</button></a>
      <a href="limbo-lost.php"><button type="submit">Lost Something</button></a>
      <a href="limbo-found.php"><button type="submit">Found Something</button></a>
    </span>
    <h1>Admin Login</h1>
  </div>

  <div class = 'body'>
  <!-- PHP for processing login -->
  <?php
    # Connect to MySQL server and the database
    require('includes/connect_db.php') ;

    # Connect to MySQL server and the database
    require('includes/login-tools.php') ;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      # Creates variables for POSTed username and the SHA256 hash of the password
      $user = $_POST['first_name'];
      $pass = hash('sha256', $_POST['pass']);

      # Validates login, returns -1 if login fails
      $pid = validate($user, $pass) ;
      
      if($pid == -1)
        echo '<p style=color:red>Login failed please try again.</p>' ;
      else {
        load();
      }
    }
  ?>
  <!-- Get inputs from the user. -->
  <form action="limbo-admin-login.php" method="POST">
    <table id = 'adminLogin'>
    <tr>
      <td>Username: </td><td><input type="text" name="first_name"></td>
    </tr>
    <tr>
      <td>Password: </td><td><input type="password" name="pass"></td>
    </tr>
    </table>
    <p><button type="submit">Submit</button></p>
    </form>
  </div>
</body>
</html>