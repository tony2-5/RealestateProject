<?php
require_once('../../lib/nav.php');
// sessions so post data is preserved when new property form is submitted
session_start();
#functionality for when submit button pressed
if(isset($_POST['submitButton'])) {
  if(isset($_POST["house"])) {
    $_SESSION["style"]=$_POST["style"];
  } else if(isset($_POST["apartment"])) {
    $_SESSION["aFloors"]=$_POST["aFloors"];
    $_SESSION["securityDeposit"]=$_POST["securityDeposit"];
  } else if(isset($_POST["condo"])) {
    $_SESSION["units"] =$_POST["units"];
    $_SESSION["cFloors"]=$_POST["cFloors"];
    $_SESSION["fees"]=$_POST["fees"];
  }
  $_SESSION["address"] = $_POST["address"];
  $_SESSION["tax"] = $_POST["tax"];
  $_SESSION["fBaths"] = $_POST["fBaths"];
  $_SESSION["hBaths"] = $_POST["hBaths"];
  $_SESSION["bedrooms"] = $_POST["bedrooms"];
}
if(isset($_POST['submitButtonOwner'])) { 
  $ssn = decrypt($encryptionKey,$_POST["agent"]);
  //populating all the property,owner,and agent relationships
  $stmt = mysqli_prepare($connection,"INSERT INTO PROPERTY VALUES(?,?,?,?,?)");
  mysqli_stmt_bind_param($stmt,"sssss",$_SESSION["address"], $_SESSION["tax"], $_SESSION["fBaths"],$_SESSION["hBaths"],$_SESSION["bedrooms"]);
  if(!mysqli_stmt_execute($stmt)) {
    header("Location: newProperty.php?message=fail");
  }

  if(isset($_SESSION["style"])) {
    $stmt = mysqli_prepare($connection,"INSERT INTO HOUSE VALUES(?,?)");
    mysqli_stmt_bind_param($stmt,"ss",$_SESSION["address"],$_POST["style"]);
    if(!mysqli_stmt_execute($stmt)) {
      header("Location: newProperty.php?message=fail");
    }
  } else if(isset($_SESSION["aFloors"])) {
    $stmt = mysqli_prepare($connection,"INSERT INTO APARTMENT VALUES(?,?,?)");
    mysqli_stmt_bind_param($stmt,"sss",$_SESSION["address"], $_SESSION["aFloors"], $_POST["securityDeposit"]);
    if(!mysqli_stmt_execute($stmt)) {
      header("Location: newProperty.php?message=fail");
    }
  } else if(isset($_SESSION["cFloors"])){
    $stmt = mysqli_prepare($connection,"INSERT INTO CONDO VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["address"], $_SESSION["units"], $_SESSION["cFloors"],$_SESSION["fees"]);
    if(!mysqli_stmt_execute($stmt)) {
      header("Location: newProperty.php?message=fail");
    }
  }

  $stmt = mysqli_prepare($connection,"INSERT INTO OWNER VALUES(?,?,?,?)");
  mysqli_stmt_bind_param($stmt,"ssss",$_POST["oSSN"], $_POST["oName"], $_POST["age"],$_POST["gender"]);
  if(!mysqli_stmt_execute($stmt)) {
    header("Location: newProperty.php?message=fail");
  }

  $stmt = mysqli_prepare($connection,"INSERT INTO OWNS VALUES(?,?,?,NULL)");
  mysqli_stmt_bind_param($stmt,"sss",$_POST["oSSN"],$_SESSION["address"], $_POST["startOwnership"]);
  if(!mysqli_stmt_execute($stmt)) {
    header("Location: newProperty.php?message=fail");
  }

  $stmt = mysqli_prepare($connection,"INSERT INTO OWNER_WORKS_WITH VALUES(?,?,?,NULL)");
  mysqli_stmt_bind_param($stmt,"sss",$ssn, $_POST["oSSN"],date("Y-m-d"));
  if(mysqli_stmt_execute($stmt)) {
    session_unset();
    session_destroy();
    header("Location: newProperty.php?message=success");
  } else {
    header("Location: newProperty.php?message=fail");
  }
} 
?>

<link rel="stylesheet" href="./style.css">
<div class="container">
    <div class="row" style="justify-content: center;">
        <div class="col-md-6">
            <div class="well-block">
                <div class="well-title">
                    <h2>Owner Info</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="ownerInfo.php" method="post">
                    <!-- Form start -->
                    <!-- Text input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="oSSN">Owner SSN</label>
                            <input id="oSSN" maxlength="9" minlength="9" name="oSSN" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                     <!-- Text input-->
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="oName">Full Name</label>
                            <input id="oName" name="oName" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Numeric input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="age">Age</label>
                            <input id="age" name="age" type="number" min=18 max=130 class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Select Basic -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                            </select>
                        </div>
                    </div>
                    <!-- Date input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="startOwnership">Start Ownership</label>
                            <input id="startOwnership" name="startOwnership" type="date" class="form-control input-md" required>
                        </div>
                    </div>
                   <!-- Select Basic -->
                    <div class="col-md-6">
                      <div class="form-group" id="agentDiv">
                          <label class="control-label" for="agent">Helping Agent</label>
                          <select id="agent" name="agent" class="form-control">
                              <?php
                              $query = "SELECT Name,Agent_SSN FROM AGENT";
                              $result = mysqli_query($connection,$query);
                              while($row=mysqli_fetch_assoc($result)) {
                                  // encrypting ssn for security
                                  $ssn=encrypt($encryptionKey,$row["Agent_SSN"]);
                                  // echo name and last 4 ssn digits
                                  echo "<option value=$ssn>$row[Name] *****".substr($row["Agent_SSN"],5)."</option>";
                              }
                              ?>
                          </select>
                      </div>
                    </div>
                    <!-- Button -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- Redirect to page where realtor can pick from available times on that date -->
                            <button id="submitButtonOwner" name="submitButtonOwner" class="btn btn-default">Add Owner Info</button>
                        </div>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>
<script>
    //jquery agent input search
    $("#agent").chosen();
</script>