<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
    header("Cache-Control: no-cache");
    header("Expires: -1");
    session_start(); //continue session

    // expire the page as soon as it is loaded

    //check if is set
    if (!isset($_SESSION['personName']) && !isset($_SESSION['hobby']) )
    {
       //send back and exit
        header("Location: exercise3.html");
        exit();
    }
    // check if it is set
    if( isset( $_SESSION['personName'] ) )
    {
        $name = $_SESSION['personName'];
    }
    //check if it is set
    if( isset( $_SESSION['hobby'] ) )
    {
        $hobby = $_SESSION['hobby'];
    }

    //destroy session and unset variables
   	session_unset();
	  session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="styles.css">
  <title>Week 11 Exercise 3 form</title>
</head>
<body>
  <p> Hello <?php echo $name; ?>. Your hobby is <?php echo $hobby; ?>.<p>
  <br>
  <a href="exercise3.html">Go to the starting form</a>
</body>
</html>
