<!--
Landing page for Limbo
By Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Limbo</title>
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
  <!-- Header -->
  <div class = 'header'>
    <span class = 'titles'>
      <a href="limbo.php"><button type="submit">Home</button></a>
      <a href="limbo-lost.php"><button type="submit">Lost Something</button></a>
      <a href="limbo-found.php"><button type="submit">Found Something</button></a>
      <a href="limbo-admin-login.php"><button type="submit">Admin Login</button></a>
    </span>
    <h1>Welcome to Limbo!</h1>
    <h3>If you lost or found something, you're in luck: this is the place to report and look for it!</h3>
    <p>If you want to view or edit an item, please click on the name of it</p>
  </div>
  <div class = 'body'>

  <!-- PHP for table, and viewing/editing a single record -->
  <?php
    # Connect to MySQL server and the database
    require('includes/connect_db.php') ;

    # Includes these helper functions
    require('includes/helpers.php') ;

    # Show basic stuff view from home page
    show_home_page($dbc) ;

    # Gets all location names from locations table
    $query = 'SELECT * FROM locations';
    $all_locations = mysqli_query($dbc, $query);

    # Upon POST, gets values for POSTED data and creates record from it
    if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
      $id = $_POST['id'];
      $location_id = $_POST['location_id'];
      $description = $_POST['description'];
      $identifiers = $_POST['identifiers'];
      $create_date = $_POST['date'] . ' ' . $_POST['time'];
      $room = $_POST['room'];
      $finder = $_POST['finder'];
      $status = $_POST['status'];
      $bounty = $_POST['bounty'];

      # Validates input, catches empty field or updates record
      if (empty($description) or empty($identifiers) or empty($create_date) or empty($room)) {
        echo '<p style="color:red">Please fill in any empty fields.</p>' ;
      } else {
        # Updates record, then refreshes page
        $result = update_record($dbc, $id, $location_id, $description, $identifiers, $create_date, $room, $finder, $status, $bounty) ;
        header("location: limbo.php");
      }
    }

    # Creates individual record and update function upon hyperlink click
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (isset($_GET['id'])) {
        $id = $_GET['id'];
          
        # Create query from passed id, then stores results in fetch_array
        $item_query = 'SELECT * FROM stuff WHERE id =' . $id;
        $results = mysqli_query($dbc, $item_query);
        $item = mysqli_fetch_array($results, MYSQLI_ASSOC);

        # Shows detailed view for selected record
        show_record($dbc, $id, $item['location_id']);
          
        # Update buttons, hidden field passes id infomation to edit page.
        echo '<p></p>';
        echo '<tr>';
        echo '<td><form action="limbo.php" method="GET"><button type="submit" name="update">Edit Record</button> <input type="hidden" value="'. $id .'" name="id"/></form></td>';  
        echo '</tr>';
        echo '<p></p>';

        # Catches server GET, updates record accordingly
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
          if (isset($_GET['update'])) {
            # Formats DATETIME into date at $datetime[0] and time at $datetime[1]
            $datetime = explode(' ', $item['create_date']);
            echo '<form action="limbo.php" method="POST">';
            echo '<input type = "hidden" value = "'. $_GET['id'] .'" name = "id"/>';
            echo '<table>';
            echo '<tr>';
            echo '<td>Item Description: </td><td><input type="text" name="description" value="' . $item['description'] . '"></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Item Identifiers: </td><td><textarea type="text" name="identifiers">' . $item['identifiers'] . '</textarea></td>';
            echo '</tr>'; 
            echo '<tr>';
            echo '<td>Date Lost/Found: </td><td><input type="date" name="date" value="' . $datetime[0] . '"></td>';
            echo '</tr>'; 
            echo '<tr>';
            echo '<td>Time Lost/Found: </td><td><input type="time" name="time" value="' . $datetime[1] . '"></td>';
            echo '</tr>'; 
            echo '<tr>';
            echo '<td><label>Location Lost/Found: </label></td>';
            echo '<td><select name="location_id">';
            # Populates location selector with names from location table
            while ($location = mysqli_fetch_array($all_locations, MYSQLI_ASSOC)):;
              echo '<option value= "'.$location["id"]. '">' .$location["name"]. '</option>';
            # Then we have to end the loop  
            endwhile;
            echo '</select></td>';
            echo '</td></tr>';
            echo '<tr>';
            echo '<tr>';
            echo '<td>Room: </td><td><input type="text" name="room" value="' . $item['room'] . '"></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Bounty: </td><td><input type="text" name="bounty" value="' . $item['bounty'] . '"></td>';
            echo '<tr>';
            echo '<tr>';
            echo '<td><label>Status: </label></td>';
            echo '<td><select name = "status"><option value= "Lost">Lost</option><option value= "Found">Found</option><option value= "Claimed">Claimed</option></select>';
            echo '</td>';
            echo '<tr>';
            echo '</tr>';
            echo '<td>(Finder) Name & Contact: </td><td><textarea type="text" name="finder">' . $item['finder'] . '</textarea></td>';
            echo '</tr>';
            echo '</table>';
            echo '<p><button type="submit">Update</button><input type="hidden" value="'. $id .'" name="id"/></p>';
            echo '</form>';
          }
        }
      }
    }

    # Close the connection
    mysqli_close($dbc) ;
  ?>
  </div>
</body>
</html>
