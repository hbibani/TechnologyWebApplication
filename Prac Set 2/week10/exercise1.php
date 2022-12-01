<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
$numrows = 0;
require_once("conn.php"); // establish connection by including these values
if( isset($_GET['staffID'] ) && $_SERVER['REQUEST_METHOD'] == "GET") //check if is set
{
  $staffNum = $dbConn->escape_string($_GET['staffID']); //check incase injection

  if(is_numeric($staffNum)) //check if is numeric
  {
    //create sql statement
    $sql = "SELECT orderID, orderDate, shippingDate, staff.staffName ";
    $sql = $sql . "FROM purchase ";
    $sql = $sql . "INNER JOIN staff ON purchase.staffID = staff.staffID ";
    $sql = $sql . "WHERE purchase.staffID = $staffNum ";
    $sql = $sql . "ORDER BY orderDate ASC";
    $results = $dbConn->query($sql)  or die ('Problem with query: ' . $dbConn->error); //execute query
    $numrows = $results->num_rows; //get num_rows to see if any values came out
  }

  $dbConn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Week 11 Exercise 1</title>
  <link rel="stylesheet" href="styles.css">
  </head>
  <body>

    <!-- if results > 0 then display -->
  <?php if( isset($_GET['staffID'] )): ?>
   <?php  if (($numrows > 0)):?>
      <h1>Purchases Of StaffID: <?php echo $_GET['staffID'] ?></h1>
      <table>
      <tr>
          <th>orderID</th>
          <th>Order Date</th>
          <th>Shipping Date</th>
          <th>Staff Name</th>
      </tr>
      <!-- fetch results, retrieve results one by one -->
      <?php while($row = $results->fetch_assoc()):?>
      <tr>
      <td><?php echo $row["orderID"]?></td>
      <td><?php echo $row["orderDate"]?></td>
      <td><?php echo $row["shippingDate"] ?> </td>
      <td><?php echo $row["staffName"] ?> </td>
      </tr>
      <?php endwhile;?>
    <?php else: ?>
      <!-- if nothing to show prompt -->
      <p> No results to show.</p>
      <a href="exercise1.html"> Enter Variables here </a>
    <?php endif ?>
  <?php else: ?>
    <!-- if someone has directly came here -->
    <p> No variables have been set. Please go to the link below to set the variables.</p>
    <a href="exercise1.html"> Enter Variables here </a>
  <?php endif ?>
    </table>
    </body>
</html>
