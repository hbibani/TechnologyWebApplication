<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php

$dbConn = new mysqli("localhost", "TWA_student", "TWA_2020_Autumn", "electrical");

if($dbConn->connect_error)
{
  die("Failed to connect to database " . $dbConn->connect_error);
}

?>
