<?php
//tab for scheduled visits, can search for visits on certain date, with certain agent
include_once("../../lib/nav.php");

//delete functionality
if(isset($_POST["delete"])) {
  $decryptedssn=decrypt($encryptionKey,$_POST["ssn"]);
  $stmt = mysqli_prepare($connection,"DELETE FROM VISITS WHERE Customer_SSN=? AND Full_address=? AND date=? AND time=?");
  mysqli_stmt_bind_param($stmt,"ssss",$decryptedssn, $_POST["address"], $_POST["date"],$_POST["time"]);
  if(mysqli_stmt_execute($stmt)) {
  echo "<div id='deleteSuccess' class='alert alert-success' role='alert'>
  Delete Success!
  </div>";
  } else {
    echo "<div id='deleteFail' class='alert alert-danger' role='alert'>
    Delete Failed.
    </div>";
  }
}
?>
<script>
  // jquery to check if success or fail message exists and to have it fade out
  if ($("#deleteSuccess").length) {
    $('#deleteSuccess').delay(5000).fadeOut(400)
  } else if($("#deleteFail").length) {
    $('#deleteFail').delay(5000).fadeOut(400)
  }
</script>

<link rel="stylesheet" href="./style.css">
<!--drop down to change search criteria-->
<form method='post' class="form-inline">
  <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
  <select name="searchCriteria" class="form-control">
    <option selected disabled>Search Criteria</option>
    <option value="aName">Agent name</option>
    <option value="date">Date</option>
    <option value="time">Time</option>
    <option value="cName">Customer name</option>
    <option value="Full_address">Address</option>
  </select>
  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
</form>
</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Customer Name</th>
      <th scope="col">Agent Name</th>
      <th scope="col">Address</th>
    </tr>
  </thead>
  <tbody>
    <?php
    //dynamically generate webpage
    if(isset($_POST['searchCriteria']) && ($_POST['searchCriteria']=='date'||$_POST['searchCriteria']=='time'||$_POST['searchCriteria']=='Full_address')) {
      // search is used on the date, time, or address field
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM VISITS WHERE ".$_POST['searchCriteria']." LIKE ? ORDER BY DATE DESC, TIME");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='cName'){
      //if search is used on customer name
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM VISITS WHERE Customer_SSN IN 
      (SELECT Customer_SSN FROM CUSTOMER WHERE NAME LIKE ?) ORDER BY DATE DESC, TIME");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='aName'){
      //if search is used on agent name
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM VISITS WHERE Customer_SSN IN 
      (SELECT Customer_SSN FROM AGENT_HELPS ah,AGENT a WHERE ah.Agent_SSN=a.Agent_SSN AND NAME LIKE ?) 
      ORDER BY DATE DESC, TIME");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    }else {
      $query = "SELECT * FROM VISITS ORDER BY DATE DESC, TIME";
      $result = mysqli_query($connection,$query);
    }

    while($row=mysqli_fetch_assoc($result)) {
    echo "<tr>";
      echo "<td>$row[date]</td>";
      echo "<td>$row[time]</td>";
      
      // get name from customer table
      $stmt = mysqli_prepare($connection,"SELECT Name FROM CUSTOMER WHERE Customer_SSN = ?");
      mysqli_stmt_bind_param($stmt,"s",$row["Customer_SSN"]);
      mysqli_stmt_execute($stmt); 
      $result2 = mysqli_stmt_get_result($stmt);
      $customerName = mysqli_fetch_assoc($result2);
      echo "<td>$customerName[Name] *****".substr($row["Customer_SSN"],5)."</td>";

      // get customers agent using Agent and AGENT_HELPS table
      $stmt = mysqli_prepare($connection,"SELECT Agent_SSN,Name FROM AGENT WHERE Agent_SSN IN 
      (SELECT Agent_SSN FROM AGENT_HELPS WHERE Customer_SSN=?)");
      mysqli_stmt_bind_param($stmt,"s",$row["Customer_SSN"]);
      mysqli_stmt_execute($stmt); 
      $result3 = mysqli_stmt_get_result($stmt);
      $agentNameSSN = mysqli_fetch_assoc($result3);
      echo "<td>$agentNameSSN[Name] *****".substr($agentNameSSN["Agent_SSN"],5)."</td>";

      echo "<td>$row[Full_address]</td>";

      //delete button and functionality
      echo "<td>";
        echo "<form method='post' style='margin: 0;'>";
        echo "<div>";
        // button, all this code is just for the svg garbage element
          echo "<button type='submit' class='btn btn-danger'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
          <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
          <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
          </svg></button>";
          //add hidden input to let page know to delete
          echo "<input type='hidden' name='delete' value='delete'>";
          // encrypting ssn to pass in post request
          $ssn=encrypt($encryptionKey,$row["Customer_SSN"]);
          echo "<input type='hidden' name='ssn' value=$ssn>";
          echo "<input type='hidden' name='date' value=$row[date]>";
          echo "<input type='hidden' name='time' value=$row[time]>";
          echo "<input type='hidden' name='address' value='$row[Full_address]'>";
        echo "</div>";
        echo "</form>";
      echo "</td>";

    echo "</tr>";
    }
   ?> 
  </tbody>
</table>