<?php
// sessions to hold ssn, address, and date so data is preserved when time form is submitted
session_start();
require_once('../../lib/nav.php');
?>
<?php

if (isset($_POST['customer'])) {
  $ssn = decrypt($encryptionKey,$_POST["customer"]);
  $_SESSION["ssn"] = $ssn;
  $_SESSION["address"] = $_POST["address"];
  $_SESSION["date"] = $_POST["date"];
}
  #functionality for when submit button pressed
if(isset($_POST['submitButton'])) { 
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
?>
<form method="post">
  <?php
    $stmt = mysqli_prepare($connection,"SELECT time FROM VISITS WHERE date = ?");
    mysqli_stmt_bind_param($stmt,"s",$_SESSION["date"]);
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
    # creating array of times that where already scheduled
    $usedTimesArray=array();
    while($row = mysqli_fetch_assoc($result)) {
      array_push($usedTimesArray,$row["time"]);
    } 
  ?>
  <div class="col-md-6">
    <div class="form-group">
        <label class="control-label" for="time">Available Times</label>
        <select id="time" name="time" class="form-control">
            <!-- make it so we redirect to page with available times -->
            <?php
              #functionality to display only available times:
              for($i=8;$i<20;$i++) {
                if($i>9) {
                  if (!in_array($i.":00:00",$usedTimesArray)) {
                    echo "<option value=".$i.":00>".$i.":00 to ".($i+1).":00</option>";
                  }
                }else {
                  if (!in_array("0".$i.":00:00",$usedTimesArray)) {
                    echo "<option value=".$i.":00>".$i.":00 to ".($i+1).":00</option>";
                  }
                }
              }
            ?>
        </select>
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