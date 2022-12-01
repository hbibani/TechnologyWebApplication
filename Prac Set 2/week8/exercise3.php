<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   require_once("conn.php"); //include connection
   $sql = "SELECT name, quantityInStock, price "; //produce SQL statement
   $sql = $sql . "FROM product ";
   $sql = $sql . "WHERE quantityInStock > 10 ";
   $sql = $sql . "ORDER BY quantityInStock ASC";
   $results = $dbConn->query($sql) or die ('Problem with query: ' . $dbConn->error);
   $numrows = $results->num_rows; //get nums incase no results to show
   $dbConn->close(); // close connection
 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Week 8 Exercise 1</title>
  <link rel="stylesheet" href="styles.css">
  </head>
  <body>
      <!-- scan for results -->
    <?php if($numrows > 0):?>
    <h1>Product table</h1>
    <table>
    <tr>
          <th>Name </th>
          <th>Quantity In Stock</th>
          <th>Price</th>
    </tr>
    <?php
        while ($row = $results->fetch_assoc()) {
    ?>
    <tr>
    <td><?php echo $row["name"]?></td>
    <td><?php echo $row["quantityInStock"]?></td>
    <td><?php echo $row["price"] ?> </td>
    </tr>
    <?php
    }
    ?>
    </table>
        <!-- no results to show if numrows = 0 -->
    <?php else:?>
      <p> There are no results to show. </p>
    <?php endif ?>
  </body>
</html>
