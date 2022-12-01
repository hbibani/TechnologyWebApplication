<?php  $row = mysqli_fetch_assoc($resultPass)?>
<?php echo $row['password'];
     $pass = "cowfarm";
     $pwdCheck = password_verify('cowfarm', $row['password']);
     echo $pwdCheck;
?>
<?php endif?>


$sqlAlbums = "SELECT DISTINCT album.album_name, album.album_id, artist.artist_name, artist.artist_id, album.thumbnail ";
$sqlAlbums = $sqlAlbums . "FROM track ";
$sqlAlbums = $sqlAlbums . "INNER JOIN artist ON track.artist_id = artist.artist_id ";
$sqlAlbums = $sqlAlbums . "INNER JOIN album  ON  track.album_id = album.album_id ";
$sqlAlbums = $sqlAlbums . "WHERE track_title = ? OR artist.artist_name = ? OR album.album_name = ?; ";

$stmtAlbums = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtAlbums, $sqlAlbums))
{
   die ('Problem with query: ' . mysqli_error($conn));
  header("Location: search.php?error=sqlerror");
  exit();
}
else
{
  mysqli_stmt_bind_param($stmtAlbums , "sss" , $keyword , $keyword , $keyword );    //SQL INJECTION KILLER
  mysqli_stmt_execute( $stmtAlbums );
  $resultAlbums  =  mysqli_stmt_get_result( $stmtAlbums );
  $numrowsAlbums = mysqli_num_rows( $resultAlbums );
  mysqli_stmt_close($stmtAlbums);
}


$keyword = $_POST['keyword'];
$sqlArtists = "SELECT DISTINCT artist.artist_name, artist.thumbnail, artist.artist_id  ";
$sqlArtists = $sqlArtists . "FROM track ";
$sqlArtists = $sqlArtists . "INNER JOIN artist ON track.artist_id = artist.artist_id ";
$sqlArtists = $sqlArtists . "INNER JOIN album  ON  track.album_id = album.album_id ";
$sqlArtists = $sqlArtists . "WHERE track_title = ? OR artist.artist_name = ? OR album.album_name = ?; ";

$stmtArtists = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtArtists, $sqlArtists))
{
   die ('Problem with query: ' . mysqli_error($conn));
   header("Location: search.php?error=sqlerror");
   exit();
}
else
{
  mysqli_stmt_bind_param($stmtArtists , "sss" , $keyword , $keyword , $keyword );    //SQL INJECTION KILLER
  mysqli_stmt_execute( $stmtArtists );
  $resultArtists  =  mysqli_stmt_get_result( $stmtArtists );
  mysqli_stmt_close($stmtArtists);
}
