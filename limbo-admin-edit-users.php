<!--
Editing user page for Limbo
By Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Admin-Edit Users</title>
    <link rel="stylesheet" href="css/custom.css">
  </head>
<body> 
  <!-- Header -->
  <div class = 'header'>
    <span class = 'titles'>
      <a href="limbo.php"><button type="submit">Home</button></a>
      <a href="limbo-lost.php"><button type="submit">Lost Something</button></a>
      <a href="limbo-found.php"><button type="submit">Found Something</button></a>
      <a href="limbo-admin.php"><button type="submit">Admin Portal</button></a>
    </span>
    <h1>Admin Portal</h1>
    <p>Edit users</p>
  </div>
  <div class = "body">
    <!-- PHP for table, and adding/updating/deleting users -->
    <?php
      # Connect to MySQL server and the database
      require('includes/connect_db.php') ;

      # Includes these helper functions
      require('includes/helpers.php') ;

      # Show detailed table for editing stuff table
      show_user_table($dbc) ;

      # Update and claim buttons, hidden field passes id infomation to edit page.
      echo '<p></p>';
      echo '<tr>';
      echo '<td><form action="limbo-admin-edit-users.php" method="GET"><button type="submit" name="add">Add User</button></form></td>';  
      echo '</tr>';
      echo '<p></p>';

      if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['delete'])) {
          $user_id = $_GET['user_id'];
          delete_user_record($dbc, $user_id);

          # Refreshes page upon user deletion
          $URL="limbo-admin-edit-users.php";
          echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
          echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        } else if (isset($_GET['add'])) {

          # Gets user input
          echo '<form action="limbo-admin-edit-users.php" method="POST">';
          echo '<table>';
          echo '<tr>';
          echo '<td>First Name: </td><td><input type="text" name="first_name""></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Last Name: </td><td><input type="text" name="last_name"></td>';
          echo '</tr>'; 
          echo '<tr>';
          echo '<td>Email (Cannot enter the same as an existing user): </td><td><input type="text" name="email"></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Password: </td><td><input type="password" name="pass"></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Confirm Password: </td><td><input type="password" name="confirm"></td>';
          echo '</tr>'; 
          echo '</table>';
          echo '<p><button type="submit">Submit</button></p>';
          echo '</form>';

        } else if (isset($_GET['edit'])) {
          $user_id = $_GET['user_id'];

          # Create query from passed id, then stores results in $item array
          $item_query = 'SELECT * FROM users WHERE user_id =' . $user_id;
          $results = mysqli_query($dbc, $item_query);
          $item = mysqli_fetch_array($results, MYSQLI_ASSOC);
          
          # Prepopulates form with queried data
          echo '<form action="limbo-admin-edit-users.php" method="POST">';
          echo '<input type = "hidden" value = "'. $_GET['user_id'] .'" name = "user_id"/>';
          echo '<table>';
          echo '<tr>';
          echo '<td>First Name: </td><td><input type="text" name="first_name" value="' . $item['first_name'] . '"></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Last Name: </td><td><textarea type="text" name="last_name">' . $item['last_name'] . '</textarea></td>';
          echo '</tr>'; 
          echo '<tr>';
          echo '<td>Email (Cannot enter the same as an existing user): </td><td><input type="text" name="email" value="' . $item['email'] . '"></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Password: </td><td><input type="password" name="pass"></td>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>Confirm Password: </td><td><input type="password" name="confirm"></td>';
          echo '</tr>'; 
          echo '</table>';
          echo '<p><button type="submit">Submit</button></p>';
          echo '</form>';
        }
      }

      # Updates record upon post
      if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $confirm = $_POST['confirm'];

        # Validates input, avoids empty fields and/or mismatched password
        if (empty($first_name) or empty($pass) or empty($email) or empty($confirm)) {
          echo '<p style="color:red">Please fill in first name, email, and password/password confirmation.</p>' ;
        } else if ($pass != $confirm) {
          echo '<p style="color:red">Passwords do not match. Please try again</p>';
        } else {
          
          # Updates or creates user, then refreshes page after
          if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $result = update_user_record($dbc, $user_id, $first_name, $last_name, $email, hash('sha256', $_POST['pass']));
            $URL="limbo-admin-edit-users.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
          } else {
            $result = insert_user_record($dbc, $first_name, $last_name, $email, hash('sha256', $_POST['pass']));
            $URL="limbo-admin-edit-users.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
          }
        }
      }

      # Close the connection
      mysqli_close($dbc) ;
    ?>
  </div>
</body>
</html>
