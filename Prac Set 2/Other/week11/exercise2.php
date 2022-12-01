<!--
Name: Heja Bibani
Student Number: 16301173
Class: Tues 4pm
 -->

<?php
// Put your connection and query code here
$dbConn = new mysqli("localhost", "TWA_student", "TWA_2020_Autumn", "electrical");

if($dbConn->connect_error)
{
  die("Failed to connect to database " . $dbConn->connect_error);
}

$result = $dbConn->query("SELECT staffID, staffName FROM staff ") or die('Query failed: '.$dbConn->error);
$results = $dbConn->query($sql)
$dbConn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exercise 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<p> Click a link to find orders placed by the staff member:</p>
</br>
<?php
    $staffID = -1; // variable for storing the staff number
    if ($result->num_rows)
    { // if any record is found
        // create the table
        echo "<table><thead><tr>";

        // fetch the table header from the sql result
        while ($thead = $result->fetch_field())
        {
            if ($thead->name != "staffID") // staff number is used for making the hyperlink.
                echo "<th> $thead->name </th>";
        }
        echo "</tr></thead><tbody>";

        // fetch the table contents from the sql result
        //there is a simpler way of doing this , i did it this way to learn the how php operates
        while ($tdata = $result->fetch_assoc())
        {
            echo "<tr>";

            foreach ($tdata as $key=>$value)
            {
                if ($key != "staffID")
                {
                    if ($key == "staffName")
                    {
                        // make the hyperlink of exercise-1.php
                        echo "<td> <a href='exercise-1.php?staffID={$staffID}'> $value </a></td>"; // staff names with hyperlinks
                    }
                }
                else
                {
                    $staffID = $value; // record the staff ID for making the hyperlink
                }
            }

            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    else
    {
        echo "<p> No records is found in the database. </p>"
    }
?>
</body>
</html>
