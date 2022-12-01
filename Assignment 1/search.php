<?php
  require "header.php";
?>

<?php
if (isset($_POST['search-submit']) && $_SERVER["REQUEST_METHOD"] == "POST"  )
{
  // Note: I have used the bind param function to sanitize
  //get the keyword seperate variables where used for distinguishing it to the programmer

  $artist =   $_POST['keyword'];
  $song   =   $_POST['keyword'];
  $album   =   $_POST['keyword'];

  if(!empty($artist))
  {
    //This will pop unwanted duplications without distinct, I added distinct to ensure that only single matches pop up
    $sqlArtists = "SELECT DISTINCT artist.thumbnail, artist.artist_name, artist.artist_id ";
    $sqlArtists = $sqlArtists . "FROM track ";
    $sqlArtists = $sqlArtists . "INNER JOIN album ON album.album_id = track.album_id ";
    $sqlArtists = $sqlArtists . "INNER JOIN artist ON artist.artist_id = track.artist_id ";
    $sqlArtists = $sqlArtists . "WHERE artist.artist_name=? OR album.album_name=? OR track.track_title=?";

    $stmtArtists = mysqli_stmt_init( $conn );
    if (!mysqli_stmt_prepare($stmtArtists,  $sqlArtists))
    {
        //send back if error with query
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: search.php?error=sqlerror");
      exit();
    }
    else
    {

        //get results with statement  and then close statement
      mysqli_stmt_bind_param(  $stmtArtists, "sss", $artist, $album, $song );
      mysqli_stmt_execute(  $stmtArtists );
      $resultArtists =  mysqli_stmt_get_result($stmtArtists);
      $numrowsArtists = mysqli_num_rows( $resultArtists);

    }
  }
  else
  {
      //automatic creation of 0 so that html can display appropriate message
      $numrowsArtists = 0;
  }

  if(!empty($album))
  {
      //Search matches for the albums
      $sqlAlbum = "SELECT DISTINCT album.thumbnail, album.album_name, album.album_id, artist.artist_name, artist.artist_id ";
      $sqlAlbum = $sqlAlbum . "FROM track ";
      $sqlAlbum = $sqlAlbum . "INNER JOIN album ON track.album_id = album.album_id ";
      $sqlAlbum = $sqlAlbum . "INNER JOIN artist ON artist.artist_id = track.artist_id ";
      $sqlAlbum = $sqlAlbum . "WHERE artist.artist_name=? OR album.album_name=? OR track.track_title=?";

      $stmtAlbums = mysqli_stmt_init( $conn );
      if (!mysqli_stmt_prepare($stmtAlbums, $sqlAlbum))
      {
          //send back if error with query
         die ('Problem with query: ' . mysqli_error($conn));
        header("Location: search.php?error=sqlerror");
        exit();
      }
      else
      {
              //get results with statement
        mysqli_stmt_bind_param(  $stmtAlbums, "sss", $album, $song, $artist );
        mysqli_stmt_execute(  $stmtAlbums);
        $resultAlbums  =  mysqli_stmt_get_result($stmtAlbums);
        $numrowsAlbums = mysqli_num_rows($resultAlbums);

      }
  }
  else
  {
    //automatic creation of 0 so that html can display appropriate message
      $numrowsAlbums = 0;
  }

  if(!empty($song))
  {
      //search matches for tracks
      $sqlSongs = "SELECT track_title, artist.artist_name, track.track_id, track.artist_id, artist.artist_id ,artist.thumbnail ";
       $sqlSongs =  $sqlSongs . "FROM track ";
       $sqlSongs =  $sqlSongs . "INNER JOIN album ON track.album_id = album.album_id ";
       $sqlSongs =  $sqlSongs . "INNER JOIN artist ON artist.artist_id = track.artist_id ";
       $sqlSongs =  $sqlSongs . "WHERE artist.artist_name=? OR album.album_name=? OR track.track_title=?";

      $stmtSongs = mysqli_stmt_init( $conn );
      if (!mysqli_stmt_prepare($stmtSongs, $sqlSongs))
      {
                  //send back if error with query
         die ('Problem with query: ' . mysqli_error($conn));
        header("Location: search.php?error=sqlerror");
        exit();
      }
      else
      {
              //get results with statement and then close statement
        mysqli_stmt_bind_param($stmtSongs, "sss", $song, $album, $artist );
        mysqli_stmt_execute($stmtSongs);
        $resultSongs  =  mysqli_stmt_get_result($stmtSongs);
        $numrowsSongs = mysqli_num_rows($resultSongs);
      }
  }
  else
  {
      //automatic creation of 0 so that html can display appropriate message
      $numrowsSongs = 0;
  }


  //close connection
  mysqli_stmt_close($stmtAlbums);
  mysqli_stmt_close($stmtSongs);
  mysqli_stmt_close($stmtArtists);
  mysqli_close($conn);

}
?>
    <main>
    <div class="wrapper-main">
    <section class="section-default">
        <div>
          <p> Welcome to 247MUSIC where we play music, well you guessed it, 247. The party don't stop at 247MUSIC; we party, well you guessed it, 247.
             We have a bunch of bonaza's for everyones ears this week, the two song of the weeks are both on the search page and login page. You can
             do a lot of things here, search for music, listen to music, make playlists if you're a member. We recommend you become a member because of all the
             juicy benefits. We don't mess with ADs, there are no ads here, but there are ads on spotify our major sponsor.</p>
        <div>
          <!-- Song of the week -->
        <h2> SEARCH PAGE </h2>
        <div class="youtube">
          <iframe id="ytplayer" type="text/html" width="640" height="360"
           src="https://www.youtube.com/embed/6wmRQUc96yM"
           frameborder="0"></iframe>
         </div>
         <div>
          <p> You can find Arist, Albums and Song matches. Scroll the list, when it pops up for you.</p>
        </div>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post" onsubmit="return formValid(this)">
            <h3>Keyword Search</h3>
            <!-- The values are re-displayed after hitting enter for ease of use-->
            <em class="req">* required field</em>
            <div>
              <label for="artist"><span class="req">*</span>Keyword:</label>
              <?php if(isset($_POST['keyword'])):?>
              <input type="text" id="keyword" name="keyword" value="<?php echo $_POST['keyword'] ?>"placeholder="keyword...." >
             <?php else:?>
              <input type="text" id="keyword" name="keyword" placeholder="Keyword...." >
            <?php endif;?>
          </div>
            <div>
              <input type="submit" value="Submit" name="search-submit"  >
              <span class="error" id="submitInv"> Must enter a value.</span>
            </div>
          </form>
          <br>
          <br>
          <div class="tableflex">
            <!-- Pop the results if there are any -->
            <!-- Artist Section -->
          <?php  if (isset($_POST["search-submit"]) &&  $numrowsArtists > 0 )
            {
            ?>
              <h2>Artists</h2>
              <table>
              <tr>
                    <th>Thumbnail</th>
                    <th>Arist Name</th>
              </tr>
              <?php while($row = mysqli_fetch_assoc($resultArtists)):?>
              <tr>
              <td> <?php  echo '<img src="/twa/thumbs/artists/'. $row['thumbnail']. '">'?></td>
              <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
              </tr>
              <?php endwhile;?>
              </table>
              <?php
                }
              ?>
                            <!-- Album Section -->
              <?php  if (isset($_POST["search-submit"]) &&  $numrowsAlbums > 0 )
                {
                ?>
                  <h2>Albums</h2>
                  <table>
                  <tr>
                        <th>Thumbnail</th>
                        <th>Album</th>
                        <th>Arist</th>
                  </tr>
                  <?php while($row = mysqli_fetch_assoc($resultAlbums)):?>
                  <tr>
                  <td> <?php  echo '<img src="/twa/thumbs/albums/'. $row['thumbnail']. '">'?></td>
                  <td> <?php  echo '<a href="play.php?albumid='. $row['album_id'] .'">'?> <?php echo $row['album_name']?></a></td>
                  <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
                  </tr>
                  <?php endwhile;?>
                  </table>
                  <?php
                    }
                  ?>
                  <?php
                      if (isset($_POST["search-submit"]) &&  $numrowsSongs==0 && $numrowsArtists  == 0 && $numrowsAlbums == 0 )
                      {
                        echo '<div><p>There are no results, try searching again.</p> </div>';
                      }
                   ?>
                                       <!-- Song Section -->
              <?php  if (isset($_POST["search-submit"]) &&  $numrowsSongs > 0 )
                {
                ?>
                  <h2>Songs</h2>
                  <table>
                  <tr>
                        <th>Thumbnail</th>
                        <th>Song Title</th>
                        <th>Arist</th>
                  </tr>
                  <?php while($row = mysqli_fetch_assoc($resultSongs)):?>
                  <tr>
                  <td> <?php  echo '<img src="/twa/thumbs/artists/'. $row['thumbnail']. '">'?></td>
                  <td> <?php  echo '<a href="play.php?songid='. $row['track_id'] .'">'?><?php echo $row['track_title']?></a></td>
                  <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
                  </tr>
                  <?php endwhile;?>
                  </table>
                  <?php
                    }
                  ?>
            </div>
           </section>
          </div>

    </main>

<?php
  // And just like we include the header from a separate file, we do the same with the footer.
  require "footer.php";
?>
