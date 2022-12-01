<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
          if( isset($_POST['personName'] ) && !empty($_POST['personName']))
          {
              $_SESSION['personName'] = $_POST['personName'];
          }

          if(isset($_POST['hobby']) && !empty($_POST['hobby']))
          {
            $_SESSION['hobby'] = $_POST['hobby'];
          }
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
  <a href="exercise3a.php">Lets visit the second page</a>
</body>
</html>
