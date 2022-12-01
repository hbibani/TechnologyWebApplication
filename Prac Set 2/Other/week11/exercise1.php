<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php

require_once("conn.php");
if( isset($_GET['staffID'] ) && $_SERVER['REQUEST_METHOD'] == "GET")
{
  $staffID = $dbConn->escape_string($_GET['staffID']);
  if(is_numeric($staffID))
  {
    $sql = "SELECT orderID, orderDate, shippingDate, staffName FROM purchase INNER JOIN staff ON purchase.staffID = staff.staffID WHERE staffID = $staffID ORDER BY orderDate ASC" or die ('Problem with query: ' . $dbConn->error);

    $results = $dbConn->query($sql)

  }
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
      <h1>Product table</h1>
      <table>
      <tr>
          <th>orderID</th>
          <th>Order Date</th>
          <th>Shipping Date</th>
          <th>Staff Name</th>
      </tr>
      <?php while($row = $results->fetch_assoc()):?>
      <tr>
      <td><?php echo $row["orderID"]?></td>
      <td><?php echo $row["orderDate"]?></td>
      <td><?php echo $row["shippingDate"] ?> </td>
      <td><?php echo $row["staffName"] ?> </td>
      </tr>
      <?php endwhile;?>
    <?php
      $dbConn->close();
    ?>
    </table>
    </body>
</html>
