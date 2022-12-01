<?php
  // First we start a session which allow for us to store information as SESSION variables.
  session_start();
  // "require" creates an error message and stops the script. "include" creates an error and continues the script.
  require "includes/dbh.inc.php";
  require "includes/nocache.inc.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results.">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>Live Stream Music, radio, playlists, songs.</title>
    <link rel="stylesheet" href="style.css">
    <script src="formValidation.js"></script>
  </head>
  <body>
    <h1>247MUSIC</h1>
    <?php if(isset($_SESSION['id'])):?>
  <p id="usernamedisplay"> Hi! <strong> <em> <?php echo $_SESSION['id'] ?></em></strong>,  <strong> Your Category: <?php echo $_SESSION['categ'] ?></strong> </p>

    <?php endif ?>
    <!-- Here is the header where I decided to include the login form for this -->
    <header>
      <nav class="nav-header-main">
        <ul>
          <?php if (isset($_SESSION['id']))
          {
            echo  '<li><a href="playlist.php">Play List</a></li>';
          }
          ?>
          <?php if (!isset($_SESSION['id']))
          {
            echo  '<li><a href="login.php">Log-in</a></li>';
            echo  '<li><a href="login.php">Sign-Up</a></li>';
          }
          ?>
          <li><a href="search.php">Search</a></li>
      </nav>
      <div class="logoutbutton">
        <?php
         if (isset($_SESSION['id'])) {
          echo '<form action="includes/logout.inc.php" method="post">
            <button type="submit" name="login-submit">Logout</button>
          </form>';
        }
        ?>
      </div>
    </header>
