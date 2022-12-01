<?php
  require "header.php";
?>
<?php
if (isset($_POST['login-submit'])  && $_SERVER["REQUEST_METHOD"] == "POST" )
{


  $uid = $_POST['uid'];
  $password = $_POST['pwd'];

  // Then we perform a bit of error handling to make sure we catch any errors made by the user.
  if (empty($uid) || empty($password))
  {
    header("Location: login.php?error=emptyfields&mailuid=".$uid);
    exit();
  }
  else
  {

    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT * FROM membership WHERE username=?;";

    // Then we prepare our SQL statement AND check if there are any errors with it.
    if (!mysqli_stmt_prepare($stmt, $sql))
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: login.php?error=sqlerror");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt, "s", $uid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if ($row = mysqli_fetch_assoc($result))
      {
         echo $row['password'];
         echo $row['username'];
         $pwdCheck = password_verify($password, $row['password']);
         if($pwdCheck)
         {
           session_start();
           // And NOW we create the session variables.
           $_SESSION['id'] = $row['username'];
           $_SESSION['uid'] = $row['firstname'];
           $_SESSION['lastname'] = $row['lastname'];
           $_SESSION['categ'] = $row['category'];
           header("Location: login.php?login=success");
           exit();
         }
         else if(!$pwdCheck)
         {
           header("Location: login.php?error=wrongpwd");
           exit();
         }

      }
      else
      {
        header("Location: login.php?error=wronguidpwd");
        exit();
      }
    }
  }
  // Then we close the prepared statement and the database connection!
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>
    <main>
      <div class="wrapper-main">
        <div>
          <h1> Login Page</h1>
        </div>
        <section class="section-default">
          <div class="youtube">
            <iframe id="ytplayer" type="text/html" width="640" height="360"
   		src="https://www.youtube.com/embed/uNd2yqABbPA?autoplay=1&origin=http://example.com"
   		frameborder="0"></iframe>
           </div>
        <?php  if (!isset($_SESSION['id'])) :?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
          <h4>LOGIN DETAILS</h4>
          <em class="req">* required field</em>
          <div>
            <label for="uid"><span class="req">*</span>Username:</label>
            <input type="text" id="uid" name="uid" placeholder="Username..." >
            <span class="error" id="uidInv"> Must enter valid username</span>
          </div>
          <div>
            <label for="pwd"><span class="req">*</span>Password:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Password...">
            <span class="error" id="pwdInv"> Must enter a password</span>
          </div>
          <div>
            <input type="submit" value="Log-in" name="login-submit"  >
            <?php if( isset( $_GET['error'] ) )
            { echo '<span id="loginerror">Username or password incorrect.</span>';}?>
          </div>
          <br>
          <br>
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
