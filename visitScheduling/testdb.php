<?php
  // code to test database connection is working
  require("../lib/initdb.php");

  $query = "SELECT * from COMPANY";
  $result = mysqli_query($connection,$query);
  if(mysqli_num_rows($result)>0) {
    $row=mysqli_fetch_assoc($result);
    echo $row["Name"];
  }
  
  mysqli_close($connection);
?>