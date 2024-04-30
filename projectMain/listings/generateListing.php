<?php
// sessions to hold agent ssn, address, and price so data is preserved when form is submitted
session_start();
require_once('../../lib/nav.php');
?>
<?php

if (isset($_POST['agent'])) {
  $ssn = decrypt($encryptionKey,$_POST["agent"]);
  $_SESSION["date"] = date("Y-m-d");
  $_SESSION["ssn"] = $ssn;
  $_SESSION["address"] = $_POST["address"];
  $_SESSION["price"] = $_POST["price"];
}
  #functionality for when submit button pressed
if(isset($_POST['submitButton'])) { 
    $stmt = mysqli_prepare($connection,"INSERT INTO LISTING VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["date"], $_SESSION["address"], $_SESSION["ssn"],$_SESSION["price"]);
    session_unset();
    session_destroy();
    if(mysqli_stmt_execute($stmt)) {
      header("Location: createListing.php?message=success");
      echo "<div id='deleteSuccess' class='alert alert-success' role='alert'>
      Delete Success!</div>";
  
    } else {
      header("Location: createListing.php?message=fail");
      echo "<div id='deleteFail' class='alert alert-danger' role='alert'>
      Delete Failed.</div>";
    }
} 
?>