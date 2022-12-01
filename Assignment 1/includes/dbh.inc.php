<?php
$dBServername = "localhost";
$dBUsername = "TWA_student",;
$dBPassword = "TWA_2020_Autumn";
$dBName = "247Music";

// Create connection
$conn = mysqli_connect($dBServername, $dBUsername, $dBPassword, $dBName);

// Check connection
if (!$conn)
{
    die("Connection failed: " . mysqli_connect_error());
}
?>
