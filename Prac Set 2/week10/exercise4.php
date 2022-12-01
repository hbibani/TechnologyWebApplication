<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
  $customerID = "";
  $dbConn = new mysqli("localhost", "TWA_student", "TWA_2020_Autumn", "electrical");
  //connect to data base if error display message
  if($dbConn->connect_error)
  {
    die("Failed to connect to database " . $dbConn->connect_error);
  }

  if( $_SERVER["REQUEST_METHOD"] == "POST" )
  {
    if(isset($_POST['customerID']))
    {
      $customerID = $dbConn->escape_string($_POST['customerID']); //sanitize results
    }

    if(isset($_POST['customerID']) && is_numeric($customerID) && !empty($customerID))
    {
      //produce sql statements
      $sql = "SELECT firstName, lastName, suburb FROM customer WHERE customerID = $customerID";
      $results = $dbConn->query($sql) or die('Query failed: '.$dbConn->error); //execute results
    }
  }
  //close connection
  $dbConn->close();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="styles.css">
  <title>Week 11 Exercise 4 form</title>
</head>
<body>
  <?php if(isset($_POST['customerID']) && !empty($_POST['customerID']) ):?>
  <form id="customer" action="trivia1.php" method="post">
    <p>Find Customer Details</p>
    <?php $row = $results->fetch_assoc()?>
    <div>
    <p>
      <!-- we display the names in the value section as done below -->
      <label for="customerID">First Name:</label>
      <input type="text" id="firstname" name="firstname" value=<?php echo $row["firstName"]?> >
    </p>
    </div>
    <div>
    <p>
      <label for="customerID">Last Name:</label>
      <input type="text" id="firstname" name="firstname" value=<?php echo $row["lastName"]?> >
    </p>
    </div>
    <div>
    <p>
      <label for="customerID">Suburb:</label>
      <input type="text" id="firstname" name="firstname" value=<?php echo $row["suburb"]?> >
    </p>
    </div>
  </form>
  <!-- if no variables have been set display message -->
<?php else: ?>
  <p> No variables have been set. Please go to the link below to set the variables.</p>
  <a href="exercise4.html"> Enter Variables here </a>
<?php endif?>
</body>
</html>
