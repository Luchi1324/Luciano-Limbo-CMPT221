<?php
# Connect to MySQL server and the database
require('includes/connect_db.php') ;

# Automatically runs limbo.sql script when accessed via localhost
$query = '';
$sqlScript = file('limbo.sql');
foreach ($sqlScript as $line)	{
	# Gets first and last two elements of each line
	$startWith = substr(trim($line), 0 ,2);
	$endWith = substr(trim($line), -1 ,1);
	
    # If a comment is detected, the line is skipped over
	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
		continue;
	}
	
    # If a semicolon is found, means command ends and the line is run as an SQL query
	# Alternatively, if the query fails it fails gracefully and lets us know
	$query = $query . $line;
	if ($endWith == ';') {
		mysqli_query($dbc, $query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
		$query= '';		
	}
}

# If sql script is imported successfully, echos message, then redirects to limbo after 3 seconds.
echo '<div class="success-response sql-import-response">Limbo SQL file imported successfully, launching limbo in 3 seconds</div>';
header('Refresh: 3; URL=limbo.php')
?>