<?php
  require "header.php";
?>
<?php
// Check session id
  if( isset($_SESSION['id']) )
  {
    // Note: I have used the bind param function to sanitize
    $userId  = $_SESSION['idmem'];
    $sqlList = "SELECT playlist_id, playlist_name ";
    $sqlList = $sqlList . "FROM memberPlaylist ";
    $sqlList = $sqlList . "WHERE member_id = ? ";

    $stmtList = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare($stmtList, $sqlList ) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror");
      exit();
    }
    else
    {
        //get results through statement
        mysqli_stmt_bind_param($stmtList, "s", $userId );
        mysqli_stmt_execute($stmtList);
        $resultsList = mysqli_stmt_get_result($stmtList);
        $numrowsList = mysqli_num_rows($resultsList);
    }
    mysqli_stmt_close($stmtList);

  //check to see if there are entries with playlist with same name
  if( isset($_POST["playlist-submit"]) )
  {
    $name = "";
    if(isset($_POST['playlistname']))
    {
      $name = $_POST['playlistname'];
    }

    // Test regex
    $regExp = "/^[a-zA-Z0-9 ]*$/";

    //preg match function tester to determine if regex matches
    if (!preg_match($regExp, $name))
    {
        header("Location: ./playlist.php?regex=error");
        exit();
    }

    //double security for empty fields
    if( $name == "" )
    {
        header("Location: ./playlist.php?var2=error");
        exit();
    }

    //sql production for playlist checking if it is owned by user
    $sqlCheck = "SELECT * FROM memberPlaylist WHERE playlist_name = ? AND member_id = ?";

    $stmtCheck = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare($stmtCheck, $sqlCheck ) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror1");
      exit();
    }
    else
    {
        //get results through statement
      mysqli_stmt_bind_param($stmtCheck , "ss", $name, $userId);
      mysqli_stmt_execute($stmtCheck);
      mysqli_stmt_store_result($stmtCheck);
      $resultCount = mysqli_stmt_num_rows($stmtCheck);
      mysqli_stmt_close($stmtCheck);
    }

    if($resultCount > 0)
    {
        //send user back with error and display message
        mysqli_close($conn);
        header("Location: ./playlist.php?entrytaken=error");
        exit();
    }

    //if no results then that means its clear to enter
    $memberId = $_SESSION['idmem'];
    $sqlInsList = "INSERT INTO memberPlayList (member_id, playlist_name) VALUES (?, ?);";
    $stmtInsList = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtInsList, $sqlInsList))
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror");
      exit();
    }
    else
    {
        //insert entry using prepared statements
        mysqli_stmt_bind_param($stmtInsList, "ss", $memberId, $name);
        mysqli_stmt_execute($stmtInsList);
        header("Location: ./playlist.php?listentry2=success");
    }

    mysqli_stmt_close($stmtInsList);
    mysqli_close($conn);
  }

  //search for a song to enter into playlist; the query will list matching songs with the corresponding id to put into the value
  if( isset($_GET['search-submit']) )
  {
    if(!isset($_GET['song']) && !isset($_GET['addsong']) && !isset($_GET['playlistidadd']) )
    {
      header("Location: ./playlist.php?vartex=error");
      exit();
    }

    $songSearch = $_GET['song'];
    $sqlSearch = "SELECT * FROM track INNER JOIN artist ON track.artist_id = artist.artist_id WHERE track_title =? ";

    $stmtSearch = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmtSearch,$sqlSearch ))
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror");
      exit();
    }
    else
    {
        //get results through statement
      mysqli_stmt_bind_param($stmtSearch, "s", $songSearch );
      mysqli_stmt_execute($stmtSearch);
      $resultsSearch = mysqli_stmt_get_result($stmtSearch);
      $numrowsSearch= mysqli_num_rows($resultsSearch);
      mysqli_stmt_close($stmtSearch);
    }

      mysqli_close($conn);
  }

  //check to see if there are entries with playlist with same name
  if( isset($_GET["addsong-submit"]) )
  {

    if(isset($_GET['playlistidadd']))
    {
        $playlistId = $_GET['playlistidadd'];
    }
    else
    {
        //send back
        header("Location: ./playlist.php?errorstate=sqlerror");
        exit();
    }

    if(isset($_GET['addsong']))
    {
      $track = $_GET['addsong'];
    }
    else
    {
      header("Location: ./playlist.php?errorstate=sqlerror");
      exit();
    }
    //check to see if the guy owns the playlist
    $userId = $_SESSION['idmem'];
    $sqlOwns = "SELECT * FROM memberPlaylist WHERE member_id=? AND playlist_id=?";
    $stmtOwn = mysqli_stmt_init($conn);

    if( !mysqli_stmt_prepare($stmtOwn,$sqlOwns) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmtOwn, "ss", $userId, $playlistId );
      mysqli_stmt_execute($stmtOwn);
      $resultsAgain= mysqli_stmt_get_result($stmtOwn);
      $numrowsAgain = mysqli_num_rows($resultsAgain);
    }

    if($numrowsAgain == 0 )
    {
        header("Location: ./playlist.php?ownserror=nomatch&trackidplay=$track&playlistidin=$playlistId");
        exit();
    }

    //check if track exists
    $sqltname = "SELECT * FROM track WHERE track_id=? ";

    $stmttname = mysqli_stmt_init( $conn );

    if ( !mysqli_stmt_prepare($stmttname, $sqltname ) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror&trackidplay=$track&playlistidin=$playlistId");
      exit();
    }
    else
    {
        //get results via statement
        mysqli_stmt_bind_param($stmttname, "s", $track );
        mysqli_stmt_execute($stmttname);
        $resultstname = mysqli_stmt_get_result($stmttname);
        $numrowsname = mysqli_num_rows($resultstname);
    }

    if($numrowsname == 0)
    {
        //send user back with error and display message
        header("Location: ./playlist.php?songEdup=error&trackidplay=$track&playlistidin=$playlistId");
        exit();
    }

    //check if the track is already in the playlist [no duplicates allowed]
    $sqladd = "SELECT * FROM playlist WHERE playlist_id=? AND track_id =?";
    $stmtadd = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare(  $stmtadd , $sqladd ) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror1&trackidplay=$track&playlistidin=$playlistId");
      exit();
    }
    else
    {
        //get the results via statement
      mysqli_stmt_bind_param(  $stmtadd  , "ss", $playlistId, $track);
      mysqli_stmt_execute(  $stmtadd );
      $resultsAdd = mysqli_stmt_get_result($stmtadd);
      $numrowsAdd = mysqli_num_rows($resultsAdd);
      mysqli_stmt_close($stmtadd);
    }

    if($numrowsAdd != 0)
    {
        //send user back with error and display message
        header("Location: ./playlist.php?songdup=error&trackidplay=$track&playlistidin=$playlistId");
        exit();
    }


    //if no results then that means its clear to enter
    $sqlInstrack = "INSERT INTO playlist (playlist_id, track_id) VALUES (?, ?);";
    $stmtInstrack = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtInstrack, $sqlInstrack))
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./playlist.php?error=sqlerror&trackidplay=$track&playlistidin=$playlistId");
      exit();
    }
    else
    {
        //insert entry using prepared statements
        mysqli_stmt_bind_param($stmtInstrack, "ss", $playlistId, $track);
        mysqli_stmt_execute($stmtInstrack);
        header("Location: ./playlist.php?listentry=success&addsongsubmit=x&trackidplay=$track&playlistidin=$playlistId");
    }

    mysqli_stmt_close($stmtInstrack);
    mysqli_close($conn);

  }
}
else
{
   header("Location: ./login.php?entry=error");
   exit();
}

?>
    <main>
      <!-- Note: This page has a lot of functionality and because of this an excessive amount of code has been created to produce the functionality -->
      <div class="wrapper-main">
        <section class="section-default">
                <!-- Display the promp to create playlists or add song to playlist -->
          <h2> PLAYLIST PAGE </h2>
          <!-- Conditialal used to ensure the right things pop up at the right time -->
          <div>
            <p> Welcome to the playlist page,  click on one of the buttons below, other forms will appear, follow the instructions when prompted and you can create a playlist or add a song.</p>
          </div>
          <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" >
            <input type="submit" value="Create Playlist" name="createpl"  >
            </form>
          </div>
          <div>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" >
                  <input type="submit" value="Add Song to Playlst" name="addpl"  >
              </form>
          </div>
          <!-- Display create playlist prompts if create playlist has been selected -->
          <?php  if ( (isset( $_SESSION['id'] ) && isset($_GET["createpl"])) || (isset($_GET['regex'])) || (isset($_GET['entrytaken']) ||  isset($_GET['listentry2']))  ) :?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return formValid(this)">
            <h4>CREATE PLAYLIST</h4>
            <em class="req">* required field</em>
            <div>
              <label for="playlistname"><span class="req">*</span>Playlist name:</label>
              <input type="text" id="playlistname" name="playlistname" placeholder="Playlist name.." onblur="return formValid(this)">
              <?php if( isset( $_GET['regex'] ) )
                    {
                      echo '<span id="loginerror"> Must contain alphanumeric characters. </span>';
                    }
              ?>
            </div>
            <div>
              <!-- Error Messages to ensure no duplicates or other problems exist -->
              <input type="submit" value="Submit" name="playlist-submit">
              <?php if( isset( $_GET['entrytaken'] ) )
                    {
                      echo '<span id="loginerror">Play List with that name already exists .</span>';
                    }
                    else if(isset($_GET['listentry2']))
                    {
                        echo '<span id="loginerror">Successfully added playlist.</span>';
                    }
              ?>
              <span class="error" id=playlistnameInv> Must enter a value to be checked.</span>
            </div>
           </form>
         <?php endif ?>
         <!-- Display prompts to search for a desired song -->
         <?php if( isset( $_SESSION['id'] ) && isset($_GET["addpl"]) ||  isset($_GET["search-submit"])):?>
           <div>
           <p>If you know the track id, go straight to the link <a href="playlist.php?trackidplay=puttrackidhere">here</a>.</p>
           <p> Instructions:</p>
           <ol>
             <li>Search an item</li>
             <li>After clicking the search button a song table will appear, click on any of the links in the table; this will place the trackid in the insert page for you</li>
             <li>Another form will appear, and in this form you add the desired playlist using the playlist number from the playlist table</li>
           </ol>
           </div>
           <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" onsubmit="return formValid(this)">
           <div>
             <label for="song">Song title:</label>
             <?php if(isset($_GET['song'])):?>
             <input type="text" id="song1" name="song" value="<?php echo $_GET['song']; ?>" placeholder="Song Title....">
             <?php else:?>
             <input type="text" id="song1" name="song" placeholder="Song Title...." onsubmit="return formValid(this)">
             <?php endif?>
           </div>
           <div>
             <input type="submit" value="Submit" name="search-submit"  >
             <!-- Error Messages to ensure no duplicates or other problems exist -->
             <?php if (isset($_GET["search-submit"]) &&  $numrowsSearch==0 )
             {
               echo '<span id="loginerror">No results to show.</span>';
             }
             ?>
            <span class="error" id="song1Inv"> Must enter song</span>
           </div>
          </form>
              <!--Display the songs for the add to playlist function of the site  -->
           <?php  if (isset($_GET["search-submit"]) &&  $numrowsSearch > 0 )
             {
             ?>

              <!--Display the songs for the add to playlist function of the site  -->
               <h2>Song</h2>
               <div class="tableflex">
               <table>
               <tr>
                     <th>Song id</th>
                     <th>Artist</th>
                     <th>Song Title</th>
               </tr>

Project Plan

- The project plan was changed and altered because a team member left
- The project proposal was changed after the group meeting

- we had a meeting about the platform
- we decided to choose android
- for four days 9 am - 11 pm we were working on designing the prototype and changing the project proposal
- The user interface was designed first and each interface was handed to other students
- The meeting was conducted with client and research was done on the api and web platform
- php was chosen for the api and web platform
- the user interface is nearly finished and back end programming for database is in the process of moving forward



               <?php while($row = mysqli_fetch_assoc(  $resultsSearch )):?>
               <tr>
               <td> <?php  echo '<a href="playlist.php?trackidplay=' . $row['track_id'] .'">'?><?php echo $row['track_id']?></a></td>
               <td> <?php  echo '<a href="playlist.php?trackidplay=' . $row['track_id'] .'">'?><?php echo $row['artist_name']?></a></td>
               <td> <?php  echo '<a href="playlist.php?trackidplay=' . $row['track_id'] .'">'?><?php echo $row['track_title']?></a></td>
               </tr>
               <?php endwhile;?>
               </table>
               </div>
             </div>
           <?php
             }
           ?>
         <?php endif ?>
         <?php
          if( isset( $_SESSION['id'] ) && isset($_GET["trackidplay"])  || ( isset( $_SESSION['id'] )  && (isset($_GET['songdup']) || isset($_GET['playlistdup'])
         || isset($_GET['var']) ||isset($_GET['listentry'])  || isset($_GET['songEdup']) || isset($_GET['ownserror']) || isset($_GET['trackidplay']) || isset($_GET['listentry']) ) ) ) :
          ?>
           <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get" onsubmit="return formValid(this)">
           <h4>ADD SONG TO PLAYLIST</h4>
           <p>Add playlist id from the list, and the song ID if it's not placed.</p>
           <em class="req">* required field</em>
           <div>
             <label for="playlistidin"><span class="req">*</span>Playlist id:</label>
              <?php if(isset($_GET['playlistidin'])):?>
             <input type="text" id="playlistidadd" name="playlistidadd" value = "<?php echo $_GET['playlistidin']?>" placeholder="Playlist id..." >
             <?php else:?>
             <input type="text" id="playlistidadd" name="playlistidadd" placeholder="Playlist id..." >
            <?php endif;?>
           </div>
           <div>
             <label for="addsong"><span class="req">*</span>Song id:</label>
             <!-- Checks to see if trackid is present to play it in the value if it was sent from another page -->
             <?php if(isset($_GET['trackidplay'])):?>
             <input type="text" id="addsong" name="addsong" value="<?php echo $_GET['trackidplay'] ?>"placeholder="Song id....">
           <?php else:?>
              <input type="text" id="addsong" name="addsong" placeholder="Song id...">
            <?php endif?>
           </div>

           <!-- If error messages are being sent display them according to the GET message sent -->
             <input type="submit" value="Submit" name="addsong-submit">
             <?php if( isset( $_GET['songdup'] ) )
                   {
                     echo '<span id="loginerror">Song already exists in playlist .</span>';
                   }
                   else if(isset($_GET['playlistdup']))
                   {
                     echo '<span id="loginerror">No such playlist in existence.</span>';
                   }
                   else if(isset($_GET['songEdup']))
                   {
                     echo '<span id="loginerror">No such song in existence.</span>';
                   }
                   else if(isset($_GET['listentry']))
                   {
                     echo '<span id="loginerror">Successfully added song to playlist.</span>';
                   }
                   else if(isset($_GET['var']))
                   {
                     echo '<span id="loginerror">Fields cannot be empty.</span>';
                   }
                   else if(isset($_GET['ownserror']))
                   {
                      echo '<span id="loginerror">You do not own such a playlist. </span>';
                   }
             ?>
             <!-- Java-script error checker -->
              <span class="error" id="addsongInv"> Both values must be filled.</span>
          </form>
        <?php endif ?>
        <!-- display playlist -->
        <?php if(isset($_SESSION['id']) && ($numrowsList > 0) && !isset($_GET['search-submit']) && !isset($_GET['addpl'])) :?>
          <div class="tableflex2">
            <h2> Playlists </h2>
          </div>
          <div class="tableflex">
          <table>
          <tr>
                <th>Playlist Id</th>
                <th>Playlist Name</th>
          </tr>
          <!-- Display Results, from playlist if it is set -->
          <?php while($row = mysqli_fetch_assoc($resultsList)):?>
          <tr>
            <td> <?php  echo '<a href="play.php?playlistid=' . $row['playlist_id'] .'">'?><?php echo $row['playlist_id']?></a></td>
            <td> <?php  echo '<a href="play.php?playlistid=' . $row['playlist_id'] .'">'?><?php echo $row['playlist_name']?></a></td>
          </tr>
          <?php endwhile;?>
          </table>
        </div>
        <!-- We can't let it show on the search page -->
      <?php elseif($numrowsList == 0 && !isset($_GET['search-submit'])):?>
        <p> You don't have any playlists, create a playlist.</p>
      <?php endif;?>
        </section>
      </div>
    </main>

<?php
  // And just like we include the header from a separate file, we do the same with the footer.
  require "footer.php";
?>
