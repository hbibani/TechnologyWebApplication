<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   $namestr = $email = $address = $favsport = $mailList = "";
   //extra security
   if($_SERVER["REQUEST_METHOD"] == "GET")
   {
     //check if variables are set, and if so, copy them to variables for form processing

       if(isset($_GET['firstname']))
       {
         $namestr = $_GET['firstname'];
       }

       if(isset($_GET['email']))
       {
         $email = $_GET['email'];
       }

       if(isset($_GET['postaddr']))
       {
         $address = $_GET['postaddr'];
       }

       if(isset($_GET['favsport']))
       {
         $favsport = $_GET['favsport'];
       }

       if(isset($_GET['emaillist']))
       {
         $mailList = $_GET['emaillist'];
       }
       else
       {
          $mailList = "No";
       }
   }

 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Week 7 Exercise 1</title>
  </head>
  <body>
    <!-- if the variables have not been set they will  be prompted -->
  <?php if(empty($namestr) && empty($email) && empty($address) && empty($favsport) ): ?>
    <p> No variable has been set, click on the link below to set the variables.</p>
    <a href="exercise1.html"> Enter Variables Here </a>
  <?php elseif(empty($namestr) || empty($email) || empty($address) || empty($favsport)): ?>
    <p> All variables must be set, click on the link below to set the variables.</p>
    <a href="exercise1.html"> Enter Variables Here </a>
  <?php else:?>
    <p>The following information was received from the form:</p>
    <p><strong>Name: </strong> <?php echo "$namestr"; ?></p>
    <p><strong>Email:</strong> <?php echo "$email"; ?></p>
    <p><strong>Address: </strong><?php echo "$address"?></p>
    <p><strong>Favourite sport:</strong><?php echo "$favsport"?></p>
    <p><strong>Mail list: </strong><?php echo "$mailList"?> </p>
  <?php endif;?>
  </body>
</html>
