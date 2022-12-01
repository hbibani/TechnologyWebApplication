<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->
 <?php
   $namestr = $email = $address = $mailList = "";
   $favsport = array();
   //obtain the values for the other input devices here
   //extra security
   if ($_SERVER["REQUEST_METHOD"] == "POST")
   {
      //check if variables are set, and if so, copy them to variables for form processing
       if(isset($_POST['firstname']))
       {
           $namestr = $_POST['firstname'];
       }

       if(isset($_POST['email']))
       {
         $email = $_POST['email'];
       }

       if(isset($_POST['postaddr']))
       {
         $address = $_POST['postaddr'];
       }

       if(isset($_POST['favsport']))
       {
         $favsport = $_POST['favsport'];
       }

       if(isset($_POST['emaillist']))
       {
         $mailList = $_POST['emaillist'];
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
  <title>Week 7 Exercise 2</title>
  </head>
  <body>
    <!-- if the variables have not been set or if the person directly went to the website they will  be prompted -->
  <?php if( !isset($_POST['firstname']) && !isset($_POST['email']) && !isset($_POST['postAddr']) && !isset($_POST['favsport']) && !isset($_POST['emaillist']) ): ?>
    <p> No variable has been set, click on the link below to set the variables.</p>
    <a href="exercise2.html"> Enter Variables Here </a>
  <?php elseif( empty($namestr) || empty($email) || empty($address) ||  empty($favsport) ): ?>
  <p> All the variables must be set, click on the link below to set the variables.</p>
  <a href="exercise2.html"> Enter Variables Here </a>
  <?php else: ?>
  <p>The following information was received from the form:</p>
  <p><strong>Name: </strong> <?php echo "$namestr"; ?></p>
  <p><strong>Email: </strong> <?php echo "$email"; ?></p>
  <p><strong>Address: </strong><?php echo "$address"?></p>
  <p><strong>Favourite Sport(s):</strong>
  <?php
    for($i=0;$i<count($favsport); $i++)
    {
      echo $favsport[$i];
      if($i < count($favsport)-1)
      {
        echo ", ";
      }
    }
  ?>
  </p>
  <p><strong>Mail list: </strong><?php echo "$mailList"?> </p>
  <?php endif;?>
  </body>
</html>
