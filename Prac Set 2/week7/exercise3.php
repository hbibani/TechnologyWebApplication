<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
   $namestr = $email = $address = $mailList = "";
   $favsport = array();
   //extra security

   //check if method is Post
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
        //if variable set then copy string
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
    <title>Week 7 Exercise 3 Form</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <h1>Week 7 Exercise 3 PHP form demo</h1>
    <form id="userinfo" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <p>Please fill in the following form. All fields are mandatory.</p>
      <p>
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="firstname">
      </p>
      <p>
        <label for="email">Email Address:</label>
        <input type="text" id="email" name="email">
      </p>
      <p>
        <label for="addr">Postal Address:</label>
        <textarea rows="5" cols="300" id="addr" name="postaddr"></textarea>
      </p>
      <p>
        <label for="sport">Favourite sport: </label>
        <select id="sport" name="favsport[]" size="4" multiple>
            <option value="soccer">Soccer</option>
            <option value="cricket">Cricket</option>
            <option value="squash">Squash</option>
            <option value="golf">Golf</option>
            <option value="tennis">Tennis</option>
            <option value="basketball">Basketball</option>
            <option value="baseball">Baseball</option>
        </select>
      </p>
      <p>
        <label for="list">Add me to the mailing list</label>
        <input type="checkbox" id="list" name="emaillist" value="Yes">
      </p>
      <p><input type="submit" name="submit-button" value="submit"></p>
    </form>
    <section id="output">
      <!-- if the variables have not been set they will  be prompted -->
      <?php
        if (isset($_POST["submit-button"]) && ( empty($namestr) || empty($email) || empty($address) ||  empty($favsport) ) ) {
      ?>
      <p> The variables are mandatory, please set ALL the variables. </p>
      <?php
      }
      else if(isset($_POST["submit-button"]))
      { ?>

        <h2>The following information was received from the form:</h2>
        <p><strong>First Name:</strong> <?php echo $namestr; ?></p>
        <p><strong>Email:</strong> <?php echo "$email"; ?></p>
        <p><strong>Address:</strong> <?php echo "$address"?></p>
        <p><strong>Favourite Sport:</strong>
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
       <?php
        }
        ?>
    </section>
  </body>
</html>
