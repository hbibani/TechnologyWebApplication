<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   require_once("conn.php");
  if( isset($_POST['quantity'] ) && $_SERVER["REQUEST_METHOD"] == "POST" )
   {
     $quantity = $dbConn->escape_string($_POST['quantity']); //clean string for injection
     if(is_numeric($quantity)) //check if it is a number
     {
       $sql = "SELECT name, quantityInStock, price "; //produce query
       $sql = $sql . "FROM product ";
       $sql = $sql . "WHERE quantityInStock > $quantity ";
       $sql = $sql . "ORDER BY quantityInStock ASC";
       //complete query or error message
       $results = $dbConn->query($sql) or die ('Problem with query: ' . $dbConn->error);
     }
   }
   $dbConn->close(); //close connection
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Week 8 Exercise 1</title>
  <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <!-- check if it is set and not empty -->
  <?php if( isset($_POST['quantity'] ) && !empty($_POST['quantity']) ):?>
  	<?php if( $quantity < 60 && is_numeric($quantity) ):?>
      <h1>Product table</h1>
      <table>
      <tr>
            <th>Name </th>
            <th>Quantity In Stock</th>
            <th>Price</th>
      </tr>
      <!-- fetch results and display one by one -->
      <?php while($row = $results->fetch_assoc()):?>
      <tr>
      <td><?php echo $row["name"]?></td>
      <td><?php echo $row["quantityInStock"]?></td>
      <td><?php echo $row["price"] ?> </td>
      </tr>
      <?php endwhile;?>
      </table>
  <?php elseif(is_numeric($quantity) && $quantity >= 60 ):?>
      <!-- display error if it is greater than equal to 60 -->
      <p> There are no products that have more than 60 in stock.</p>
      <a href="exercise4.html"> Go back </a>
  <?php elseif(!is_numeric($quantity)):?>
    <!-- display error if not quantity-->
      <p> The value entered for the quantity was not a number.</p>
      <a href="exercise4.html"> Go back </a>
 <?php endif;?>
<?php else: ?>
  <p> No variables have been set. Please go to the link below to set the variables.</p>
  <a href="exercise4.html"> Enter Variables here </a>
<?php endif;?>

    </body>
</html>
