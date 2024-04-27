<?php
require_once('../../lib/nav.php');
?>
<?php
  #functionality for when submit button pressed
  if(isset($_POST['submitButton'])) { 
      $stmt = mysqli_prepare($connection,"INSERT INTO VISITS VALUES(?,?,?,?)");
      mysqli_stmt_bind_param($stmt,"ssss",$_POST["customer"],$_POST["address"],$_POST["date"],$_POST["time"]);
      if(mysqli_stmt_execute($stmt)) {
        echo "Appointment Scheduled!";
      } else {
        echo "Failed!";
      }
  } 
?>
<form method="post">
  <?php
  /*
    echo $_POST["address"];
    echo "<br>";
    echo $_POST["customer"];
    echo "<br>";
    echo $_POST["date"];
    echo "<br>";
    */
  ?>
  <?php
              $stmt = mysqli_prepare($connection,"SELECT time FROM VISITS WHERE date = ?");
              mysqli_stmt_bind_param($stmt,"s",$_POST["date"]);
              mysqli_stmt_execute($stmt); 
              $result = mysqli_stmt_get_result($stmt);
              # creating array of times that where already scheduled
              $usedTimesArray=array();
              while($row = mysqli_fetch_assoc($result)) {
                array_push($usedTimesArray,$row["time"]);
              } 
              // if (in_array("08:00:00",$row)) { 
              //   echo "found"; 
              // } else {
              //   echo "not found";
              // }
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
  <!-- hidden fields to pass post data from previous page with next post request-->
  <input type="hidden" name="date" id="hiddenField" value="<?php echo $_POST['date'] ?>" >
  <input type="hidden" name="customer" id="hiddenField" value="<?php echo $_POST['customer'] ?>" >
  <input type="hidden" name="address" id="hiddenField" value="<?php echo $_POST['address'] ?>" >
  <div class="col-md-12">
    <div class="form-group">
        <button type="submit" id="submitButton" name="submitButton" class="btn btn-default">Schedule!</button>
    </div>
  </div>
</form>
