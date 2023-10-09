<!--
Report lost items page for Limbo
By Luciano Mattoli & Andrew Masone
-->
<!DOCTYPE html>
<html lang="en-US">
<head>
  <title>Lost Something</title>
  <link rel="stylesheet" href="css/custom.css">
</head>
<body>
  <!-- Header -->
  <div class = "header">
    <span class = 'titles'>
      <a href="limbo.php"><button type="submit">Home</button></a>
      <a href="limbo-found.php"><button type="submit">Found Something</button></a>
      <a href="limbo-admin-login.php"><button type="submit">Admin Login</button></a>
    </span>
    <h1>Report a Lost Item!</h1>
    <p>If you lost something, you're in luck: this is the place to report it!</p>
  </div>

  <!-- Main Body -->
  <div class = 'body'>
  <!-- PHP for inserting records -->
  <?php
    # Connect to MySQL server and the database
    require('includes/connect_db.php') ;

    # Includes these helper functions
    require('includes/helpers.php') ;

    # Gets all location names from locations table
    $query = 'SELECT * FROM locations';
    $all_locations = mysqli_query($dbc, $query);
    
    # Upon post, gets values for POSTED data and creates record from it
    if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
      $location = $_POST['location_id'];
      $description = $_POST['description'];
      $identifiers = $_POST['identifiers'];
      $create_date = $_POST['date'] . ' ' . $_POST['time'] . ':00';
      $room = $_POST['room'];
      $owner = $_POST['owner'];
      $bounty = $_POST['bounty'];

      # Validates input
      if (empty($description) or empty($identifiers) or empty($create_date) or empty($room) or empty($owner)) {
        echo '<p style="color:red">Please fill in any empty fields.</p>' ;
      } else {
        $result = insert_record($dbc, $location, $description, $identifiers, $create_date, $create_date, $room, $owner, '', 'Lost', $bounty) ;
        header("location: limbo.php");
      }
    }

    # Close the connection
    mysqli_close($dbc) ;
  ?>
  <!-- HTML Form for record insertion -->
  <form action="limbo-lost.php" method="POST">
    <table>
    <tr>
      <td>Item Description: </td><td><input type="text" name="description" placeholder="Describe item briefly"></td>
    </tr>
    <tr>
      <td>Item Identifiers: </td><td><textarea type="text" name="identifiers" placeholder="Any unique identifiers?"></textarea></td>
    </tr>
    <tr>
      <td>Date Lost: </td><td><input type="date" name="date" placeholder="YYYY-MM-DD"></td>
    </tr>
    <tr>
      <td>Time Lost: </td><td><input type="time" name="time" placeholder="HH:MM (24 hr time)"></td>
    </tr>
    <tr>
      <td><label>Location Lost at: </label></td>
      <td><select name="location_id">
        <!-- Populates location selector with values from location table -->
        <?php
          while ($location = mysqli_fetch_array($all_locations, MYSQLI_ASSOC)):;
            echo '<option value= "'.$location["id"]. '">' .$location["name"]. '</option>';
          # Then we have to end the loop  
          endwhile;
        ?>
      </select></td>
    </tr>
    <tr>
      <td>Room Lost in: </td><td><input type="text" name="room" placeholder="HC 2020, LWC P4"></td>
    </tr>
    <tr>
      <td>Bounty: </td><td><input type="number" name="bounty" value="0"></td>
    </tr>
    <tr>
      <td>Name & Contact: </td><td><textarea type="text" name="owner" placeholder="John Doe, text at 123-456-7789"></textarea></td>
    </tr>
    </table>
    <p><button type="submit">Submit</button></p>
  </form>
  </div>
</body>
</html>
