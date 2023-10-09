<!--
Admin Panel for Limbo, accessed from limbo-admin-login.php
Made by Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Admin Portal</title>
  <link rel="stylesheet" href="css/custom.css">
</head>
<body>
  <!-- Header -->
  <div class = 'header'>
    <span class = 'titles'>
      <a href="limbo.php"><button type="submit">Home</button></a>
      <a href="limbo-lost.php"><button type="submit">Lost Something</button></a>
      <a href="limbo-found.php"><button type="submit">Found Something</button></a>
    </span>
    <h1>Admin Portal</h1>
    <p>If you're looking to do admin functions, this is the place!</p>
  </div>

  <!-- Admin functions -->
  <div class = 'body'>
    <table id = 'adminHome'>
    <tr>
      <td><a href="limbo-admin-edit-table.php">Edit Tables</a></td>
    </tr>
    <tr>
      <td><a href="limbo-admin-edit-users.php">Edit Users</a></td>
    </tr>  
    </table>
  </div>
</body>
</html>