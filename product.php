<!DOCTYPE html>
<html>
<body>

<?php
session_start();

// initializing variables
$ucp = "";
$discordid = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', 'lexyy@2609', 'uirp');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}



$sql = "SELECT ID, ucp, DiscordID FROM playerucp";
$result = $db->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        print "<br> id: ". $row["ID"]. "<br> - Name: ". $row["ucp"]. "<br> - Discord ID: " . $row["DiscordID"] . "<br>";
      //print "<img src=\"".$row["img"]."\">";
     
    }
} else {
    print "0 results";
}



$db->close();   
        ?> 



</body>
</html>
