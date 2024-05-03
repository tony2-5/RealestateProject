<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');
// check if scheduling succes or failed
if(isset($_GET["message"])) {
if($_GET["message"]=="success") {
    echo "<div id='agentSuccess' class='alert alert-success' role='alert'>
        Agent added!
    </div>";
} elseif($_GET["message"]=="fail") {
    echo "<div id='agentFail' class='alert alert-danger' role='alert'>
        Adding new agent failed.
    </div>";
}
}
?>
<?php
#functionality for when submit button pressed
if(isset($_POST['submitButton'])) { 
    $stmt = mysqli_prepare($connection,"INSERT INTO AGENT VALUES(?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt,"sssss",$_POST["aSSN"], $_POST["aName"], $_POST["age"],$_POST["gender"],$_POST["company"]);
    if(mysqli_stmt_execute($stmt)) {
      header("Location: newAgent.php?message=success");
    } else {
      header("Location: newAgent.php?message=fail");
    }
} 
?>
<link rel="stylesheet" href="./style.css">
<div class="container">
    <div class="row" style="justify-content: center;">
        <div class="col-md-6">
            <div class="well-block">
                <div class="well-title">
                    <h2>Add New Agent</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="newAgent.php" method="post">
                    <!-- Form start -->
                     <!-- Text input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="aSSN">Agent SSN</label>
                            <input id="aSSN" maxlength="9" minlength="9" name="aSSN" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                     <!-- Text input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="aName">Full Name</label>
                            <input id="aName" name="aName" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Numeric input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="age">Age</label>
                            <input id="age" name="age" type="number" min=18 max=100 class="form-control input-md" required>
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
                    <!-- Select Basic -->
                    <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="company">Agent Company</label>
                                <select id="company" name="company" class="form-control">
                                    <?php
                                        $query = "SELECT Company_ID,Name FROM COMPANY";
                                        $result = mysqli_query($connection,$query);
                                        while($row=mysqli_fetch_assoc($result)) {
                                            echo "<option value=$row[Company_ID]>$row[Company_ID] : $row[Name]</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <!-- Button -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- Redirect to page where realtor can pick from available times on that date -->
                            <button id="submitButton" name="submitButton" class="btn btn-default">Add agent</button>
                        </div>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>
<script>
  // jquery to check if success or fail message exists and to have it fade out
  if ($("#agentSuccess").length) {
    $('#agentSuccess').delay(5000).fadeOut(400)
  } else if($("#agentFail").length) {
    $('#agentFail').delay(5000).fadeOut(400)
  }
  //jquery company input search
  $("#company").chosen();
</script>
