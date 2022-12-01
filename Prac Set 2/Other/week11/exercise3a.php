<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
    session_start();
    if (!isset($_SESSION['personName']) || !isset($_SESSION['hobby']) )
    {
        header("Location: exercise2.html");
        exit();
    }

    if( isset($_SESSION['personName'] ) && !empty($_SESSION['personName']))
    {
        $name = $_SESSION['personName']
    }

    if(isset($_SESSION['hobby']) && !empty($_SESSION['hobby']))
    {
        $hobby = $_SESSION['hobby'];
    }
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
  <a href="exercise3.html">Lets visit the second page</a>
</body>
</html>
