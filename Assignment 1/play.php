<?php
  require "header.php";
?>
<?php

$results;
// Note: I used the prepared statements to ensure that the queries have been sanitized for extra security
//if any of the values are set display the playpage
if (isset($_GET['artistid']) || isset($_GET['albumid']) || isset($_GET['songid']) || isset($_GET['playlistid']) )
{

  if(isset($_GET['artistid']))
  {
      //Search for artist and display details of the artist
      $artistid = $_GET['artistid'];
      $sqlArtist = "SELECT artist_id, artist_name , thumbnail FROM artist  WHERE artist_id = ?";

      $stmtArtist = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmtArtist, $sqlArtist))
      {
        //exit on error query
        die ('Problem with query: ' . mysqli_error($conn));
        header("Location: ./play.php?error=sqlerror");
        exit();
      }
      else
      {
                //get the results using statements
          mysqli_stmt_bind_param($stmtArtist, "s", $artistid  );
          mysqli_stmt_execute($stmtArtist);
          $resultsArtist = mysqli_stmt_get_result($stmtArtist);
          $row = mysqli_fetch_assoc($resultsArtist);
          $artistname = $row['artist_name'];
          //reverse the data to send it to position 0
          mysqli_data_seek( $resultsArtist , 0 );

      }

      //search for albums by artists
      $sqlAlbumArt = "SELECT album_name, album_id ,album_date ,thumbnail ";
      $sqlAlbumArt = $sqlAlbumArt . "FROM album ";
      $sqlAlbumArt = $sqlAlbumArt . "WHERE artist_id = ?;";

      $stmtAlbumArt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmtAlbumArt, $sqlAlbumArt))
      {

        //exit on error query
        die ('Problem with query: ' . mysqli_error($conn));
        header("Location: ./play.php?error=sqlerror");
        exit();
      }
      else
      {
                //get the results using statements
          mysqli_stmt_bind_param($stmtAlbumArt, "s", $artistid );
          mysqli_stmt_execute($stmtAlbumArt);
          $resultsAlbumArt = mysqli_stmt_get_result($stmtAlbumArt);
          $albumnumrows =  mysqli_num_rows( $resultsAlbumArt);

      }
      //close statements and connection
      mysqli_stmt_close($stmtArtist);
      mysqli_stmt_close($stmtAlbumArt);
      mysqli_close($conn);
  }

  if(isset($_GET['albumid']))
  {
    //album information SQL results
    //There are two queries the information about the album and the song it self
    $albumid = $_GET['albumid'];
    $sqlAlbum = "SELECT album_id, album_name, artist.artist_id, artist.artist_name ,album.thumbnail ";
    $sqlAlbum = $sqlAlbum . "FROM ALBUM ";
    $sqlAlbum = $sqlAlbum . "INNER JOIN artist ON artist.artist_id = album.artist_id ";
    $sqlAlbum = $sqlAlbum . "WHERE album_id = ?";

    $stmtAlbum = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare($stmtAlbum, $sqlAlbum ) )
    {
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./play.php?error=sqlerror");
      exit();
    }
    else
    {
              //get the results using statements
        mysqli_stmt_bind_param($stmtAlbum, "s", $albumid  );
        mysqli_stmt_execute($stmtAlbum);
        $resultsAlbum = mysqli_stmt_get_result($stmtAlbum);
        $row = mysqli_fetch_assoc($resultsAlbum);
        $albumName2 = $row['album_name'];
        mysqli_data_seek( $resultsAlbum , 0 );
    }

    //display the songs which are in the album that was clicked
    $sqlAlbSong = "SELECT track_id, track_title, track_length ";
    $sqlAlbSong = $sqlAlbSong . "FROM track ";
    $sqlAlbSong = $sqlAlbSong . "WHERE album_id = ?;";

    $stmtAlbumSong = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare( $stmtAlbumSong, $sqlAlbSong) )
    {
        //exit if error on query
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./play.php?error=sqlerror");
      exit();
    }
    else
    {

        //get the results
        mysqli_stmt_bind_param($stmtAlbumSong, "s", $albumid );
        mysqli_stmt_execute($stmtAlbumSong);
        $resultsAlbumSong =  mysqli_stmt_get_result($stmtAlbumSong);
    }

    //close connection and statements
    mysqli_stmt_close($stmtAlbum);
    mysqli_stmt_close($stmtAlbumSong);
    mysqli_close($conn);
  }

  if(isset($_GET['songid']))
  {
    //detailed information about the song being requested
    $trackid = $_GET['songid'];
    $sqlTrack = "SELECT track_id, track_title, track_length, spotify_track, track.album_id , album.album_name ";
    $sqlTrack = $sqlTrack . "FROM track ";
    $sqlTrack = $sqlTrack . "INNER JOIN album ON track.album_id = album.album_id ";
    $sqlTrack = $sqlTrack . "WHERE track_id = ?";

    $stmtTrack = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare($stmtTrack, $sqlTrack ) )
    {
        //exit if error on query
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./play.php?error=sqlerror");
      exit();
    }
    else
    {
        //get the results using statements
        mysqli_stmt_bind_param($stmtTrack, "s", $trackid  );
        mysqli_stmt_execute($stmtTrack);
        $resultsTrack = mysqli_stmt_get_result($stmtTrack);
        $row = mysqli_fetch_assoc($resultsTrack);
        //when we get one row we must go back to initial position
        $trackTitle = $row['track_title'];
        mysqli_data_seek( $resultsTrack , 0 );
    }

    //close connection and statement
    mysqli_stmt_close($stmtTrack);
    mysqli_close($conn);
  }

  if(isset($_GET['playlistid']) && isset($_SESSION['id']))
  {
    //search for playlist and its contents
    $playlistid = $_GET['playlistid'];
    $userId     = $_SESSION['idmem'];
    $sqlList = "SELECT   playlist.playlist_id, memberPlaylist.playlist_name, track.spotify_track, track.track_title, artist.artist_name, track.track_id, artist.artist_id, track.track_length, track.album_id ";
    $sqlList = $sqlList . "FROM playlist ";
    $sqlList = $sqlList . "INNER JOIN track ON track.track_id = playlist.track_id ";
    $sqlList = $sqlList . "INNER JOIN artist ON track.artist_id = artist.artist_id ";
    $sqlList = $sqlList . "INNER JOIN memberPlaylist ON playlist.playlist_id = memberPlaylist.playlist_id ";
    $sqlList = $sqlList . "WHERE playlist.playlist_id = ? AND member_id =?";

    $stmtList = mysqli_stmt_init( $conn );
    if ( !mysqli_stmt_prepare($stmtList, $sqlList ) )
    {
      //exit if error on query
      die ('Problem with query: ' . mysqli_error($conn));
      header("Location: ./play.php?error=sqlerror");
      exit();
    }
    else
    {
        mysqli_stmt_bind_param($stmtList, "ss", $playlistid, $userId);
        mysqli_stmt_execute($stmtList);
        $resultsList = mysqli_stmt_get_result($stmtList);
        mysqli_stmt_close($stmtList);
        $row = mysqli_fetch_assoc($resultsList);
        //get num rows
        $playlistrows =  mysqli_num_rows($resultsList);
        $playlistName = $row['playlist_name'];
        //once a row has been called need to go backwards to initialize position
        mysqli_data_seek( $resultsList , 0 );
    }

    //close connection and statement

    mysqli_close($conn);
  }
}
else
{
  header("Location: ./search.php?noquery=noquery");
  exit();
}
?>
    <main>
      <!-- The results are fetched from the queries that are executed above, the results are traversed and then displayed with the corresponding values-->
      <div class="wrapper-main">
        <section class="section-default">
          <h2> PLAY PAGE </h2>
          <p> Welcome to the play page, this gives you detailed information from your search query. When the page is loaded, it will pop the results for you; you can even
            add songs to your playlist by clicking on a song, and then clicking the link on it. The results are seperated distinctly for you in seperate tables as specified.
            If you can't find what you're looking for use the navigation to get to where you want.</p>
          <?php  if (isset($_GET['artistid']))
            {
            ?>
            <p><strong> You've selected an artist, below is the artist details and the albums that they have produced. </strong></p>
            <!-- get artist detials if set -->
              <h3>Artist Name: <?php echo $artistname ?></h3>
              <div class="tableflex2">
              <table>
              <tr>
              	    <th>Thumbnail</th>
                    <th>Arist</th>
              </tr>
              <?php while($row = mysqli_fetch_assoc($resultsArtist)):?>
              <tr>
              <td> <?php  echo '<img src="/twa/thumbs/artists/'. $row['thumbnail']. '">'?></td>
              <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
              </tr>
              <?php endwhile;?>
            </table>
            </div>
            <h3><?php echo $artistname ?> Albums</h3>
            <?php if ($albumnumrows !=0): ?>
            <div class="tableflex">
            <table>
            <tr>
                  <th>Thumbnail</th>
                  <th>Album</th>
                  <th>Year</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($resultsAlbumArt)):?>
            <tr>
            <td> <?php  echo '<img src="/twa/thumbs/albums/'. $row['thumbnail']. '">'?></td>
            <td> <?php  echo '<a href="play.php?albumid='. $row['album_id'] .'">'?> <?php echo $row['album_name']?></a></td>
            <td> <?php echo $row['album_date']?></td>
            </tr>
          <?php endwhile;?>
          </table>
          </div>
        <?php else:?>
        <p> <strong>Artist does not have any albums.</strong></p>
        <?php endif;?>
        <?php
        }
        else if(isset($_GET['albumid']))
        {
          ?>
        <p> <strong>You've selected an album, below is an album and the songs that the album contains.</strong></p>
          <!-- get album detials if set, we fetch the results from the corresponding values -->
          <h3>Album Name: <?php echo $albumName2 ?></h3>
          <div class="tableflex2">
            <table>
            <tr>
                  <th>Thumbnail</th>
                  <th>Album</th>
                  <th>Artist</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($resultsAlbum)):?>
            <tr>
            <td> <?php  echo '<img src="/twa/thumbs/albums/'. $row['thumbnail']. '">'?></td>
            <td> <?php  echo '<a href="play.php?albumid='. $row['album_id'] .'">'?> <?php echo $row['album_name']?></a></td>
            <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
            </tr>
          </table>
        </div>
        <?php endwhile;?>
          <!-- Display songs of the album -->
          <h3>Album Songs</h3>
          <div class="tableflex">
          <table>
          <tr>
                <th>Song Title</th>
                <th>Song Length</th>
          </tr>
          <?php while($row = mysqli_fetch_assoc($resultsAlbumSong)):?>
          <tr>
          <td> <?php  echo '<a href="play.php?songid='. $row['track_id'] .'">'?><?php echo $row['track_title']?></a></td>
          <td> <?php echo $row['track_length']?></td>
          </tr>
          <?php endwhile;?>
          </table>
        </div>
        </section>
      <?php
        }
        else if(isset($_GET['songid']))
        {
      ?>
      <!-- Display the songs details -->
      <p><strong> You've selected a song, below is detailed information about the song, if you're a member, you can add it to your playlist.</strong></p>
      <h3>Song Name: <?php echo $trackTitle;?></h3>
      <div class="tableflex2">
      <table>
      <tr>
            <th>Spotify</th>
            <th>Song Title</th>
            <th>Album Title</th>
            <th>Song Length</th>
            <!-- Display add song to playlist for members -->
            <?php if(isset($_SESSION['id'])):?>
            <th>Add to playlist</th>
            <?php endif?>
      </tr>
      <?php while($row = mysqli_fetch_assoc($resultsTrack)):?>
      <tr>
        <td> <?php echo '<iframe src="https://open.spotify.com/embed/track/'. $row['spotify_track'] . '" width="300"
        height="380" frameborder="0" allowtransparency="true" allow="encryptedmedia"></iframe>'?>
        </td>
        <td> <?php  echo '<a href="play.php?songid=' . $row['track_id'] .'">'?><?php echo $row['track_title']?></a></td>
        <td> <?php  echo '<a href="play.php?albumid='. $row['album_id'] .'">'?><?php echo $row['album_name']?></a></td>
        <td> <?php  echo $row['track_length']?></td>
        <?php if(isset($_SESSION['id'])):?>
        <td> <?php  echo '<a href="playlist.php?trackidplay='. $row['track_id'] .'">'?>Add to playlist</a></td>
        <?php endif?>
      </tr>
      <?php endwhile;?>
      </table>
      </div>
      <?php
      }
      else if(isset($_GET['playlistid']) && isset($_SESSION['id']))
      {
      ?>
            <!-- Display plyslists details if there are entries to show -->

      <?php if($playlistrows == 0): ?>
      <p> <strong> No songs in playlist, <a href="playlist.php?addpl=Add+Song+to+Playlst">Go here to add songs. </a> </strong></p>
      <?php else:?>
      <p> <strong>You've selected a playlist, below is detailed information about the songs contained in the playlist.</strong></p>
      <div class="tableflex2">

        <h3> Playlist Name: <?php echo $playlistName?> </h3>

      </div>
      <div class="tableflex">
      <table>
      <tr>
            <th>Spotify</th>
            <th>Song Title</th>
            <th>Artist</th>
            <th>Song length</th>
      </tr>
      <?php while($row = mysqli_fetch_assoc($resultsList)):?>
      <tr>
        <td> <?php echo '<iframe src="https://open.spotify.com/embed/track/'. $row['spotify_track'] . '" width="300"
        height="380" frameborder="0" allowtransparency="true" allow="encryptedmedia"></iframe>'?>
        </td>
        <td> <?php  echo '<a href="play.php?songid=' . $row['track_id'] .'">'?><?php echo $row['track_title']?></a></td>
        <td> <?php  echo '<a href="play.php?artistid=' . $row['artist_id'] .'">'?><?php echo $row['artist_name']?></a></td>
        <td> <?php  echo $row['track_length']?></td>
      </tr>
      <?php endwhile;?>
      </table>
    <?php endif?>
    </div>
    <?php
      }
     ?>
    </main>

<?php
  // And just like we include the header from a separate file, we do the same with the footer.
  require "footer.php";
?>
