<?php
// sessions to hold ssn, address, and date so data is preserved when time form is submitted
session_start();
require_once('../../lib/nav.php');
?>
<?php
if(isset($_GET["message"])) {
  if($_GET["message"]=="overlapping") {
    echo "<div class='alert alert-danger' role='alert'>
      Cant schedule overlapping times!
    </div>";
  }
  if($_GET["message"]=="sameCustomerOverlap") {
    echo "<div class='alert alert-danger' role='alert'>
      Same customer needs an additional 30 minutes between appointments!
    </div>";
  }
}
if (isset($_POST['customer'])) {
  $ssn = decrypt($encryptionKey,$_POST["customer"]);
  $_SESSION["ssn"] = $ssn;
  $_SESSION["address"] = $_POST["address"];
  $_SESSION["date"] = $_POST["date"];
}

//functionality for when submit button pressed
if(isset($_POST['submitButton'])) { 
    //var to keep track if overlap
    $overlap=false;
    $sameCustomer=false;
    // issues setting up trigger for database using phpmyadmin so checking for no duplicates in the code
    $stmt = mysqli_prepare($connection,"SELECT * FROM VISITS WHERE date = ? AND Full_address = ?");
    mysqli_stmt_bind_param($stmt,"ss",$_SESSION["date"],$_SESSION["address"]);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
    // if exists tuples in table already
    if(mysqli_num_rows($result)) {
      while($row = mysqli_fetch_assoc($result)) {
        //if same customer and agent need to ensure half hour gap
        if($row["Customer_SSN"]==$_SESSION["ssn"]) {
          // add 1 hour and half gap
          $varTimeGap=1.5*60*60;
          $sameCustomer=true;
        } else {
          // add 1 hour to scheduled time normally
          $varTimeGap=1*60*60;
        }
        $timeGap=1*60*60;
        $startTime=strtotime($row["time"]);
        $endTime=strtotime($row["time"]);
        $passedStartTime=strtotime($_POST["time"]);
        $passedEndTime = strtotime($_POST["time"]);
        //check for no overlap
        /*adding timegap to end times in if statement because can change if scheduling 
        for the same customer with varTimeGap needed extra 30 minutes*/
        if(($startTime<($passedEndTime+$timeGap) && $passedStartTime<($endTime+$varTimeGap))
        ||($passedStartTime<($endTime+$timeGap) && $startTime<($passedEndTime+$varTimeGap))) {
          $overlap=true;
          if($sameCustomer) {
            header("Location: timeSchedule.php?message=sameCustomerOverlap");
          } else {
            header("Location: timeSchedule.php?message=overlapping");
          }
        } 
      }
      if(!$overlap){
        $stmt = mysqli_prepare($connection,"INSERT INTO VISITS VALUES(?,?,?,?)");
        mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["ssn"], $_SESSION["address"], $_SESSION["date"],$_POST["time"]);
        if(mysqli_stmt_execute($stmt)) {
          session_unset();
          session_destroy();
          header("Location: visits.php?message=success");
        } else {
          echo "Try again!";
          // header("Location: visits.php?message=fail");
        }
      }
    } else {
      $stmt = mysqli_prepare($connection,"INSERT INTO VISITS VALUES(?,?,?,?)");
          mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["ssn"], $_SESSION["address"], $_SESSION["date"],$_POST["time"]);
          session_unset();
          session_destroy();
          if(mysqli_stmt_execute($stmt)) {
            header("Location: visits.php?message=success");
          } else {
            header("Location: visits.php?message=fail");
          }
    }
} 
?>

<form method="post">
  <div class="col-md-6">
    <div class="form-group">
        <label class="control-label" for="time">Enter Time</label>
        <input type="time" class="form-control" name="time" required>
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
        <button type="submit" id="submitButton" name="submitButton" class="btn btn-default">Schedule!</button>
    </div>
  </div>
</form>
<?php
?>