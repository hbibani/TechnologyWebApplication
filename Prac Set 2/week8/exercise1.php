<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   require_once("conn.php"); //require connection as an includes
   $sql = "SELECT * FROM product"; //write query get all entries from product
   $results = $dbConn->query($sql) or die ('Problem with query: ' . $dbConn->error); //post error if query fail
   $numrows = $results->num_rows; //check if any data to see if a prompt needs to be sent
   $dbConn->close(); //close connection once done for efficiency
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
          <th>Product Code</th>
          <th>Name </th>
          <th>Quantity In Stock</th>
          <th>Price</th>
    </tr>
    <?php //fetch row from table, and display results
        while ($row = $results->fetch_assoc()) {
    ?>
    <tr>
    <td><?php echo $row["productCode"]?></td>
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
