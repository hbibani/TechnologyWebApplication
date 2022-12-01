
<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   require_once("conn.php");
  if( isset($_POST['quantity'] )  && $_SERVER["REQUEST_METHOD"] == "POST" )
   {
     $quantity = $dbConn->escape_string($_POST['quantity']); //santize string
     if(is_numeric($quantity)) //check if numeric
     {
       //produce SQL cause the length was too long
       $sql = "SELECT name, quantityInStock, price ";
       $sql = $sql . "FROM product  ";
       $sql = $sql . "WHERE quantityInStock > $quantity ";
       $sql = $sql . "ORDER BY quantityInStock ASC";
       $results = $dbConn->query($sql) or die ('Problem with query: ' . $dbConn->error); //execute query or send error
     }
   }

  $dbConn->close(); //close connection to save resources
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Week 8 Exercise 1</title>
  <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <!-- check post back see if button is set-->
    <?php  if (isset($_POST["submit-button"]))
      {
      ?>
      <!-- check quantity and if it is numeric to proceed-->
      <?php if( $quantity < 60 && is_numeric($quantity) ):?>
        <h1>Product table</h1>
        <table>
        <tr>
              <th>Name </th>
              <th>Quantity In Stock</th>
              <th>Price</th>
        </tr>
        <?php while($row = $results->fetch_assoc()):?>
        <tr>
        <td><?php echo $row["name"]?></td>
        <td><?php echo $row["quantityInStock"]?></td>
        <td><?php echo $row["price"] ?> </td>
        </tr>
        <?php endwhile;?>
        <?php endif ?>
      </table>
  <?php
      }
  ?>
    <!-- action post back, using htmlspecialchars-->
    <form id="exercise4Form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <h1>Quantity in Stock</h1>
      <p>Please enter the quantity to check against stock levels</p>
      <p>
          <label for="quantity">Quantity: </label>
          <input type="text" value= "<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : '' ?>" name="quantity" size="10" id="quantity" maxlength="6">
            <!-- check if submit button is set-->
          <?php if (isset($_POST["submit-button"]))
          {
          ?>
          <!-- determine which error to check, NAN error OR quantity greater than 60 error-->
            <?php if(is_numeric($quantity) && $quantity >= 60):?>
                <span> There are no products that have more than 60 in stock.</span>
            <?php elseif(!is_numeric($quantity)):?>
                <span> The value entered for the quantity was not a number.</span>
            <?php endif ?>
            <?php
            }
          ?>
      </p>
    <p><input type="submit" name="submit-button"></p>
    </form>
    </body>
</html>
