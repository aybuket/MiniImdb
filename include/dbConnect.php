<?php

/*
COMP306-DATABASE MANAGEMENT SYSTEMS


More information about the parameters can be found: 
https://www.php.net/manual/en/mysqli.construct.php
*/

$host     = 'localhost';
$username = 'root';
$passwd   = '<pass>'; #This operation is not secure
$dbName   = 'mini_imdb';

$conn = mysqli_connect($host, $username, $passwd, $dbName);

if(!$conn){
    die('Connection failed: '.mysqli_connect_error());
}

#Note that we did not close the php tag.
