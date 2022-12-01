<?php
  require "header.php";
?>

<?php

  // Note: I have used the bind param function to sanitize
  if(isset($_POST["login-submit"]) && $_SERVER["REQUEST_METHOD"] == "POST")
  {
    //get the variables
    if(isset($_POST['uid']))
    {
        $uid = $_POST['uid'];
    }

    if(isset($_POST['pwd']))
    {
        $pass =$_POST['pwd'];
    }

    //check if such a username exists
    $sqlPass = "SELECT * FROM membership WHERE username=? ";
    $stmtPass = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtPass, $sqlPass))
    {
       die ('Problem with query: ' . mysqli_error($conn));
       header("Location: search.php?error=sqlerror");
       exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmtPass , "s" , $uid );    //SQL INJECTION KILLER
      mysqli_stmt_execute( $stmtPass );
      $resultPass  =  mysqli_stmt_get_result( $stmtPass );
    }

    //hash the password so we can verify it
    $row = mysqli_fetch_assoc($resultPass);
    //if numrows > 0 then it exists
    $numrows = mysqli_num_rows($resultPass);
    $test = hash('sha256', $pass );

    if($numrows > 0)
    {
      if($test == $row['password'])
      {
        $pwdCheck = 1;
      }
      else
      {
        $pwdCheck = false;
      }

      //if we good set the variables
      if($pwdCheck)
      {
        session_start();
        $_SESSION['idmem'] = $row['member_id'];
        $_SESSION['id'] = $row['username'];
        $_SESSION['uid'] = $row['firstname'];
        $_SESSION['lastname'] = $row['lastname'];
        $_SESSION['categ'] = $row['category'];
        header("Location: ./search.php?login=success");
        exit();
      }
      else
      { //send if a failure occurs
          header("Location: ./login.php?error=wronguidpwd");
          exit();
      }
    }
    else
    {
      //send if a failure occurs
      header("Location: ./login.php?error=wronguidpwd");
      exit();
    }
    //close connection and statment
    mysqli_stmt_close($stmtPass);
    mysqli_close($conn);
  }

?>
    <main>
      <div class="wrapper-main">
        <section class="section-default">
          <p> Welcome to 247MUSIC where we play music, well you guessed it, 247. The party don't stop at 247MUSIC; we party, well you guessed it, 247.
             We have a bunch of bonaza's for everyones ears this week, the two song of the weeks are both on the search page and login page. You can
             do a lot of things here, search for music, listen to music, make playlists if you're a member. We recommend you become a member because of all the
             juicy benefits. We don't mess with ADs, there are no ads here, but there are ads on spotify our major sponsor.</p>
          <h2>LOGIN PAGE</h2>
          <!-- Song of the week -->
          <div class="youtube">
            <iframe id="ytplayer" type="text/html" width="640" height="360"
             src="https://www.youtube.com/embed/-1ftPleHMq4"
             frameborder="0"></iframe>
           </div>
           <p> Log-in to get access to your playlists, or <a href="signup.php"> sign-up.</a></p>
        <?php  if (!isset($_SESSION['id'])) :?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return formValid(this)" >
          <h4>LOGIN DETAILS</h4>
          <em class="req">* required field</em>
          <div>
            <label for="uid"><span class="req">*</span>Username:</label>
            <input type="text" id="uid" name="uid" placeholder="Username..." >
          </div>
          <div>
            <label for="pwd"><span class="req">*</span>Password:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Password...">
          </div>
          <div>
            <input type="submit" value="Log-in" name="login-submit"  >
            <?php if( isset( $_GET['error'] ) )
            { echo '<span id="loginerror">Username or password incorrect.</span>';}?>
          </div>
         </form>
          <br>
          <br>
          <!-- login status at the bottom -->
          <p class="login-status">You are logged out!</p>
        <?php elseif (isset($_SESSION['id'])): ?>
          <p class="login-status">You are logged in!</p>
        <?php endif;?>
        </section>
      </div>
    </main>

<?php
  // And just like we include the header from a separate file, we do the same with the footer.
  require "footer.php";
?>
