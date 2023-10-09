<?php
$debug = true;
# Authors: Luciano Mattoli & Andrew Masone
# Contains helper functions for the limbo website

function show_home_page($dbc) {
  # Create query to get date/time, status and description
  $query = 'SELECT id, create_date, status, description, owner, finder, bounty FROM stuff';

  # Executes query
  $results = mysqli_query($dbc, $query);

  # If query fails, tells us why
  if (!$results) {
    printf("Error: %s\n", mysqli_error($dbc));
    exit();
  }

  # Show results in table format
  if ($results) {
    # Shows results if query is succesful
    echo '<table>';
    echo '<tr>';
    echo '<th>Stuff</th>';
    echo '<th>Status</th>';
    echo '<th>Date Lost</th>';
    echo '<th>Owner</th>';
    echo '<th>Finder</th>';
    echo '<th>Bounty</th>';
    echo '</tr>';
  }

  # For each row result, generate a table row
  while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
    $alink = '<a href=limbo.php?id=' . $row['id'] . '>' . $row['description'] . '</a>';
    echo '<tr>';
    echo '<td>' . $alink . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>' . $row['create_date'] . '</td>';
    echo '<td>' . $row['owner'] . '</td>';
    echo '<td>' . $row['finder'] . '</td>';
    echo '<td>$' . $row['bounty'] . '</td>';
    echo '</tr>';
  }

  # Ends table
  echo '</table>';

  # Frees up results in memory
  mysqli_free_result($results);
}

function show_edit_table($dbc) {
  # Create query to get all details from the stuff table
  $query = 'SELECT * FROM stuff';
  $query2 = 'SELECT * FROM locations';

  # Executes query
  $results = mysqli_query($dbc, $query);
  $results2 = mysqli_query($dbc, $query2);

  # If query fails, tells us why
  if (!$results) {
    printf("Error: %s\n", mysqli_error($dbc));
    exit();
  }

  # Show results in table format
  if ($results) {
    # Shows results if query is succesful
    echo '<table>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Description</th>';
    echo '<th>Status</th>';
    echo '<th>Location</th>';
    echo '<th>Room</th>';
    echo '<th>Date Lost</th>';
    echo '<th>Date Updated/Found</th>';
    echo '<th>Owner</th>';
    echo '<th>Finder</th>';
    echo '<th>Bounty</th>';
    echo '</tr>';
  }

  # For each row result, generate a table row + edit button that POSTs ID for row selected
  while ( $row = mysqli_fetch_array($results, MYSQLI_ASSOC) and $row2 = mysqli_fetch_array($results2, MYSQLI_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['description'] . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>' . $row2['name'] . '</td>';
    echo '<td>' . $row['room'] . '</td>';
    echo '<td>' . $row['create_date'] . '</td>';
    echo '<td>' . $row['update_date'] . '</td>';
    echo '<td>' . $row['owner'] . '</td>';
    echo '<td>' . $row['finder'] . '</td>';
    echo '<td>' . $row['bounty'] . '</td>';

    # Edit and delete buttons, hidden field passes id infomation to edit page.
    echo '<td id="invs"><form action="limbo-admin-edit-table.php" method="GET"><button type="submit" name="edit">Edit</button><input type="hidden" value="'. $row['id'] .'" name="id"/></form></td>';
    echo '<td id="invs"><form action="limbo-admin-edit-table.php" method="GET"><button id="remove" type="submit" name="delete">Delete</button><input type="hidden" value="'. $row['id'] .'" name="id"/></form></td>';
    echo '</tr>';
  }

  # Ends table
  echo '</table>';

  # Frees up results in memory
  mysqli_free_result($results);
}

# Displays user table for admin
function show_user_table($dbc) {
  # Create query to get date/time, status and description
  $query = 'SELECT user_id, first_name, last_name, email, reg_date FROM users';

  # Executes query
  $results = mysqli_query($dbc, $query);

  # If query fails, tells us why
  if (!$results) {
    printf("Error: %s\n", mysqli_error($dbc));
    exit();
  }

  # Show results in table format
  if ($results) {
    # Shows results if query is successful
    echo '<table>';
    echo '<tr>';
    echo '<th>User ID</th>';
    echo '<th>First Name</th>';
    echo '<th>Last Name</th>';
    echo '<th>Email</th>';
    echo '<th>Registration Date</th>';
    echo '</tr>';
  }

  # For each row result, generate a table row
  while ( $row = mysqli_fetch_array($results, MYSQLI_ASSOC) ) {
    # Formats date
    echo '<tr>' ;
    echo '<td>' . $row['user_id'] . '</td>';
    echo '<td>' . $row['first_name'] . '</td>';
    echo '<td>' . $row['last_name'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['reg_date'] . '</td>';

    # Edit and delete buttons, hidden field passes id infomation to edit page.
    echo '<td id="invs"><form action="limbo-admin-edit-users.php" method="GET"><button style="width:80%" type="submit" name="edit">Edit</button><input type="hidden" value="'. $row['user_id'] .'" name="user_id"/></form></td>';
    echo '<td id="invs"><form action="limbo-admin-edit-users.php" method="GET"><button style="background-color:#d32a2a;width:80%" type="submit" name="delete">Delete</button><input type="hidden" value="'. $row['user_id'] .'" name="user_id"/></form></td>';
    echo '</tr>';
  }

  # Ends table
  echo '</table>';

  # Frees up results in memory
  mysqli_free_result($results);
}

# Shows a table for a singular item selected by ID
function show_record($dbc, $id, $location_id) {
  # Create query to get all details from the stuff table
  $query = 'SELECT * FROM stuff WHERE id = '.$id;
  $query2 = 'SELECT * FROM locations WHERE id = '.$location_id;

  # Executes query
  $results = mysqli_query($dbc, $query);
  $results2 = mysqli_query($dbc, $query2);

  # Show results
  if ( $results ) {
    # But...wait until we know the query succeeded before
    # starting the table.
    # Shows results if query is succesful
    echo '<p></p>';
    echo '<table>';
    echo '<tr>';
    echo '<th>Description</th>';
    echo '<th>Identifiers</th>';
    echo '<th>Location</th>';
    echo '<th>Date</th>';
    echo '<th>Status</th>';
    echo '<th>Owner</th>';
    echo '<th>Room</th>';
    echo '<th>Finder</th>';
    echo '<th>Bounty</th>';
    echo '</tr>';

    # For each row result, generate a table row
    $row = mysqli_fetch_array($results , MYSQLI_ASSOC);
    $row2 = mysqli_fetch_array($results2, MYSQLI_ASSOC);
    echo '<tr>';
    echo '<td>' . $row['description'] . '</td>';
    echo '<td>' . $row['identifiers'] . '</td>';
    echo '<td>' . $row2['name'] . '</td>';
    echo '<td>' . $row['create_date'] . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>' . $row['owner'] . '</td>';
    echo '<td>' . $row['room'] . '</td>';
    echo '<td>' . $row['finder'] . '</td>';
    echo '<td>$' . $row['bounty'] . '</td>';
    echo '</tr>' ;

    # End the table
    echo '</table>';
    echo '<p></p>';

    # Free up the results in memory
    mysqli_free_result( $results );
  }
}

# Inserts a record into the stuff table
function insert_record($dbc, $location_id, $description, $identifiers, $create_date, $update_date, $room, $owner, $finder, $status, $bounty) {
  $query = 'INSERT INTO stuff (location_id, description, identifiers, create_date, update_date, room, owner, finder, status, bounty)
            VALUES (' . $location_id . ', "' . $description . '", "' . $identifiers . '","' . $create_date . '", "' . $update_date . '", "' . $room . '", "' . $owner . '", "' . $finder . '", "' . $status . '", ' . $bounty . '  )';

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Inserts a record into the user table
function insert_user_record($dbc, $first_name, $last_name, $email, $pass) {
  $query = 'INSERT INTO `users` (first_name, last_name, email, pass, reg_date)
            VALUES ("' . $first_name . '", "' . $last_name . '","' . $email . '", "' . $pass . '", Now())';

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Updates a record in the stuff table
function update_record($dbc, $id, $location_id, $description, $identifiers, $create_date, $room, $finder, $status, $bounty) {
  $query = "UPDATE `stuff` SET 
            `location_id` = '$location_id',
            `description` = '$description',
            `identifiers` = '$identifiers',
            `create_date` = '$create_date',
            `update_date` = Now(),
            `room` = '$room',
            `finder` = '$finder',
            `status` = '$status',
            `bounty` = '$bounty'
            WHERE id = $id";

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Updates a record in the user table
function update_user_record($dbc, $user_id, $first_name, $last_name, $email, $pass) {
  $query = "UPDATE `users` SET 
            `first_name` = '$first_name',
            `last_name` = '$last_name',
            `email` = '$email',
            `pass` = '$pass'
            WHERE user_id = $user_id";

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Deletes a record from the stuff table
function delete_record($dbc, $id) {
  $query = 'DELETE FROM stuff WHERE id = ' . $id;

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Deletes a record from the user table
function delete_user_record($dbc, $user_id) {
  $query = 'DELETE FROM users WHERE user_id = ' . $user_id;

  $results = mysqli_query($dbc, $query);
  check_results($results);

  return $results;
}

# Checks query results as a debugging aid
function check_results($results) {
  global $dbc;

  if($results == false) {
    show_query($results);
    echo '<p>SQL ERROR = ' . mysqli_error($dbc) . '</p>';
  }
}

# Shows the query as a debugging aid
function show_query($query) {
  global $debug;

  if($debug)
    echo "<p>Query = $query</p>";
}
?>
