<!--
This file contains PHP login helper functions.
Orginally created by Ron Coleman.
Luciano Mattoli & Andrew Masone
-->
<?php
# Includes these helper functions
require('helpers.php') ;

# Loads admin portal
function load() {
  header("Location: limbo-admin.php");
  exit();
}

# Validates the password.
# Returns -1 if validate fails, and >= 0 if it succeeds
function validate($first_name = '', $pass = '') {
    global $dbc;

    if(empty($first_name) or empty($pass))
      return -1;

    # Make the query
    $query = "SELECT `first_name`, `pass` FROM `users` WHERE `first_name` = '$first_name' AND `pass`= '$pass'";

    # Execute the query
    $results = mysqli_query($dbc, $query);
    check_results($results);

    # If we get no rows, the login failed
    if (mysqli_num_rows( $results ) == 0 )
      return -1;
}
?>