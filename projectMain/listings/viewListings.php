<?php
require_once('../../lib/nav.php');

// Alert handling based on query parameters
if(isset($_GET['update'])) {
  if ($_GET['update'] == 'success') {
      echo "<div id='updateSuccess' class='alert alert-success' role='alert'>
      Update Success!
      </div>";
  } elseif ($_GET['update'] == 'fail') {
      echo "<div id='updateFail' class='alert alert-danger' role='alert'>
      Update Failed.
      </div>";
  }
}

// delete functionality
if(isset($_POST["delete"])) {
  $decryptedssn=decrypt($encryptionKey,$_POST["ssn"]);
  $stmt = mysqli_prepare($connection,"DELETE FROM LISTING WHERE Agent_SSN=? AND list_date=? AND Full_address=? AND asking_price=?");
  mysqli_stmt_bind_param($stmt,"ssss",$decryptedssn, $_POST["date"], $_POST["address"],$_POST["price"]);
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
  } else if($("#updateSuccess").length) {
    $('#updateSuccess').delay(5000).fadeOut(400)
  } else if($("#updateFail").length) {
    $('#updateFail').delay(5000).fadeOut(400)
  } 
</script>
<link rel="stylesheet" href="./style.css">
<form method='post' class="form-inline">
  <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" required>
  <select name="searchCriteria" class="form-control" required>
    <option value="" selected disabled>Search Criteria</option>
    <option value="list_date">Date of listing</option>
    <option value="Full_address">Address</option>
    <option value="aName">Agent name</option>
    <option value="minAsking">Minimum asking price</option>
    <option value="maxAsking">Maximum asking price</option>
  </select>
  <button class="btn btn-primary mx-2 my-2 my-sm-0" type="submit">Search</button>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Listing Date</th>
      <th scope="col">Property Address</th>
      <th scope="col">Listing Agent</th>
      <th scope="col">Asking Price</th>
    </tr>
  </thead>
  <tbody>
    <?php
    //dynamically generate webpage
    if(isset($_POST['searchCriteria']) && ($_POST['searchCriteria']=='list_date'||$_POST['searchCriteria']=='Full_address')) {
      // search is used on the date, time, or address field
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM LISTING WHERE ".$_POST['searchCriteria']." LIKE ? ORDER BY LIST_DATE DESC");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='aName'){
      //if search is used on agent name
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM LISTING l WHERE l.Agent_SSN IN 
      (SELECT a.Agent_SSN FROM AGENT a WHERE a.Agent_SSN=l.Agent_SSN AND a.NAME LIKE ?) 
      ORDER BY l.LIST_DATE DESC");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='minAsking'){
      $searchString="$_POST[search]";
      $stmt = mysqli_prepare($connection,"SELECT * FROM LISTING WHERE asking_price >= ? ORDER BY list_date DESC");
      mysqli_stmt_bind_param($stmt,"d",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='maxAsking'){
      $searchString= "$_POST[search]";
      $stmt = mysqli_prepare($connection,"SELECT * FROM LISTING WHERE asking_price <= ? ORDER BY list_date DESC");
      mysqli_stmt_bind_param($stmt,"d",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    }else {
      $query = "SELECT * FROM LISTING ORDER BY LIST_DATE";
      $result = mysqli_query($connection,$query);
    }

    // Data fetching logic
    while($row=mysqli_fetch_assoc($result)) {
    echo "<tr>";
      echo "<td>$row[list_date]</td>";
      echo "<td>$row[Full_address]</td>";
      
      // get name from customer table
      $stmt = mysqli_prepare($connection,"SELECT Name FROM AGENT WHERE Agent_SSN = ?");
      mysqli_stmt_bind_param($stmt,"s",$row["Agent_SSN"]);
      mysqli_stmt_execute($stmt); 
      $result2 = mysqli_stmt_get_result($stmt);
      $agentName = mysqli_fetch_assoc($result2);
      echo "<td> $agentName[Name] *****".substr($row["Agent_SSN"],5)."</td>";

      echo "<td>$" . number_format($row['asking_price'], 2, '.', ',') . "</td>";
      
      // Delete and edit button
      
      // Each row of table for listings
      echo "<td>";
        // Edit form
        echo "<form method='post' action='editListing.php' style='display: inline; margin-right: 10px;'>";
          echo "<button type='submit' class='btn btn-primary'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
          <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/</svg></button>";
          // Hidden input to let the page know to edit
          echo "<input type='hidden' name='edit' value='edit'>";
          // Pass necessary data to edit page
          echo "<input type='hidden' name='ssn' value='" . encrypt($encryptionKey, $row["Agent_SSN"]) . "'>";
          echo "<input type='hidden' name='date' value='{$row['list_date']}'>";
          echo "<input type='hidden' name='price' value='{$row['asking_price']}'>";
          echo "<input type='hidden' name='address' value='{$row['Full_address']}'>";
        echo "</form>";

        // Delete form
        echo "<form method='post' style='display: inline;'>";
          echo "<button type='submit' class='btn btn-danger'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
          <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
          <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
          </svg></button>";
          // Hidden input to let the page know to delete
          echo "<input type='hidden' name='delete' value='delete'>";
          // Encrypted SSN to pass in post request for deletion
          echo "<input type='hidden' name='ssn' value='" . encrypt($encryptionKey, $row["Agent_SSN"]) . "'>";
          echo "<input type='hidden' name='date' value='{$row['list_date']}'>";
          echo "<input type='hidden' name='price' value='{$row['asking_price']}'>";
          echo "<input type='hidden' name='address' value='{$row['Full_address']}'>";
        echo "</form>";
      echo "</td>";

    echo "</tr>";
    }
   ?> 
  </tbody>
</table>