<!--
Editing tables page for Limbo, allows for admin to delete and edit item records
By Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Admin - Items Table</title>
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
    <p>Edit table</p>
  </div>

  <div class = 'body'>
  <!-- PHP for table, and updating/deleting items --> -->
  <?php
    # Connect to MySQL server and the database
    require('includes/connect_db.php') ;

    # Includes these helper functions
    require('includes/helpers.php') ;

    # Gets all location names from locations table
    $query = 'SELECT * FROM locations';
    $all_locations = mysqli_query($dbc, $query);

    # Show indepth table for editing stuff table
    show_edit_table($dbc) ;
      
    # Detects server GET, performs action accordingly whether it is an 'edit' GET or a 'delete' GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      if (isset($_GET['delete'])) {
        $id = $_GET['id'];
        delete_record($dbc, $id);
        # Refreshes page once record is deleted
        $URL="limbo-admin-edit-table.php";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
      } else if (isset($_GET['edit'])) {
        $id = $_GET['id'];

        # Create query from passed id, then stores results in fetch_array
        $item_query = 'SELECT * FROM stuff WHERE id =' . $id;
        $results = mysqli_query($dbc, $item_query);
        $item = mysqli_fetch_array($results, MYSQLI_ASSOC);

        # Shows individual record of item selected for edit
        show_record($dbc, $id, $item['location_id']);

        # Formats DATETIME into date at $datetime[0] and time at $datetime[0]
        $datetime = explode(' ', $item['create_date']);
          
        echo '<form action="limbo-admin-edit-table.php" method="POST">';
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
        echo '<td>(Owner) Name & Contact: </td><td><textarea type="text" name="owner">' . $item['owner'] . '</textarea></td>';
        echo '</tr>'; 
        echo '</tr>';
        echo '<td>(Finder) Name & Contact: </td><td><textarea type="text" name="finder">' . $item['finder'] . '</textarea></td>';
        echo '</tr>';
        echo '</table>';
        echo '<p><button type="submit">Submit</button></p>';
        echo '</form>';
      }
    }

    # Detects server POST, creates values from $_POST values and updates record with respective values
      if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
        $id = $_POST['id'];
        $location_id = $_POST['location_id'];
        $description = $_POST['description'];
        $identifiers = $_POST['identifiers'];
        $create_date = $_POST['date'] . ' ' . $_POST['time'];
        $room = $_POST['room'];
        $owner = $_POST['owner'];
        $finder = $_POST['finder'];
        $status = $_POST['status'];
        $bounty = $_POST['bounty'];

        # Validates input, catches empty fields or updates record
        if (empty($description) or empty($identifiers) or empty($create_date) or empty($room) or empty($owner)) {
          echo '<p style="color:red">Please fill in any empty fields.</p>' ;
        } else {
          $result = update_record($dbc, $id, $location_id, $description, $identifiers, $create_date, $room, $finder, $status, $bounty);
          # Echos new update
          echo "<p>Updated item ID# ".$id." ". $description ."</p>" ;
        }
      }

    # Close the connection
    mysqli_close($dbc) ;
  ?>
  </div>
</body>
</html>
