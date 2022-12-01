<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
  $customerID = "";
  $dbConn = new mysqli("localhost", "TWA_student", "TWA_2020_Autumn", "electrical");

  if($dbConn->connect_error)
  {
    die("Failed to connect to database " . $dbConn->connect_error);
  }

  if(isset($_POST['customerID']) && $_SERVER["REQUEST_METHOD"] == "POST")
  {
    $customerID = $dbConn->escape_string($_POST['customerID']);
  }

  if(is_numeric($customerID) && !empty($customerID))
  {
    $sql = "SELECT firstName, lastName, suburb FROM customer WHERE customerID = $customerID" or die('Query failed: '.$dbConn->error);
    $results = $dbConn->query($sql);
  }

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
  <form id="customer" action="trivia1.php" method="post">
    <p>Find Customer Details</p>
    <?php $row = $results->fetch_assoc()?>
    <div>
    <p>
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
    <p>
      <input type="submit"  value="Submit">
    </p>
  </form>
</body>
</html>
