<?php
# Connects to MySQL Database

# Connect  on 'localhost' for user 'root' with password 'password' to database 'limbo_db'.
$dbc = mysqli_connect('localhost', 'root', 'password', 'limbo_db') 

# Otherwise fails gracefully and prints error
OR die (mysqli_connect_error());

# Set encoding to match PHP script encoding
mysqli_set_charset($dbc, 'utf8');