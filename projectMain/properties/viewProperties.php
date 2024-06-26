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
  $stmt = mysqli_prepare($connection,"DELETE FROM LISTING WHERE Full_address=?");
  mysqli_stmt_bind_param($stmt,"s",$_POST["address"]);
  mysqli_stmt_execute($stmt);
  $stmt = mysqli_prepare($connection,"DELETE FROM PROPERTY WHERE Full_address=?");
  mysqli_stmt_bind_param($stmt,"s",$_POST["address"]);
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
<!--drop down to change search criteria-->
<form method='post' class="form-inline">
  <input name="search" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" required>
  <select name="searchCriteria" class="form-control" required>
    <option value="" selected disabled>Search Criteria</option>
    <option value="Name">Owner name</option>
    <option value="Full_address">Address</option>
    <option value="maxTax">Max Tax</option>
    <option value="minTax">Min Tax</option>
    <option value="Fullbath">Full Baths</option>
    <option value="Halfbath">Half Baths</option>
    <option value="Bedrooms">Bedrooms</option>
  </select>
  <button class="btn btn-primary mx-2 my-2 my-sm-0" type="submit">Search</button>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Owner</th>
      <th scope="col">Property Address</th>
      <th scope="col">Tax</th>
      <th scope="col">Full Baths</th>
      <th scope="col">Half Baths</th>
      <th scope="col">Bedrooms</th>
    </tr>
  </thead>
  <tbody>
    <?php
    //dynamically generate webpage
    //dynamically generate webpage
    if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='Full_address') {
      // search is used on the address field
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM PROPERTY WHERE ".$_POST['searchCriteria']." LIKE ?");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='Name'){
      //if search is used on owner name
      $searchString="%$_POST[search]%";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM PROPERTY WHERE Full_address IN 
      (SELECT Full_address FROM OWNS WHERE Owner_SSN IN (SELECT Owner_SSN FROM OWNER WHERE ".$_POST['searchCriteria']." LIKE ?))");
      mysqli_stmt_bind_param($stmt,"s",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && ($_POST['searchCriteria']=='Fullbath' || $_POST['searchCriteria']=='Halfbath' || $_POST['searchCriteria']=='Bedrooms')) {
      //search on full baths, half baths, or bedrooms (numeric inputs)
      $searchString="$_POST[search]";
      // concatenating search criteria instead of binding param because do not need to worry about sanitizing search criteria
      $stmt = mysqli_prepare($connection,"SELECT * FROM PROPERTY WHERE ".$_POST['searchCriteria']." LIKE ?");
      mysqli_stmt_bind_param($stmt,"d",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } 
    else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='minTax'){
      $searchString="$_POST[search]";
      $stmt = mysqli_prepare($connection,"SELECT * FROM PROPERTY WHERE Tax >= ?");
      mysqli_stmt_bind_param($stmt,"d",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    } else if(isset($_POST['searchCriteria']) && $_POST['searchCriteria']=='maxTax'){
      $searchString= "$_POST[search]";
      $stmt = mysqli_prepare($connection,"SELECT * FROM PROPERTY WHERE Tax <= ?");
      mysqli_stmt_bind_param($stmt,"d",$searchString);
      mysqli_stmt_execute($stmt); 
      $result = mysqli_stmt_get_result($stmt);
    }else {
      $query = "SELECT * FROM PROPERTY";
      $result = mysqli_query($connection,$query);
    }

    //data fetching 
    while($row=mysqli_fetch_assoc($result)) {
    echo "<tr>";
      // get name from customer table
      $stmt = mysqli_prepare($connection,"SELECT Name,Owner_SSN FROM OWNER WHERE Owner_SSN IN (SELECT Owner_SSN FROM OWNS WHERE Full_address=?)");
      mysqli_stmt_bind_param($stmt,"s",$row["Full_address"]);
      mysqli_stmt_execute($stmt); 
      $result2 = mysqli_stmt_get_result($stmt);
      $ownerIdentifiers = mysqli_fetch_assoc($result2);
      echo "<td> $ownerIdentifiers[Name] *****".substr($ownerIdentifiers["Owner_SSN"],5)."</td>";
      
      echo "<td>$row[Full_address]</td>";
      echo "<td>$" . number_format($row["tax"], 2, '.', ',') . "</td>";
      echo "<td>$row[Fullbath]</td>";
      echo "<td>$row[Halfbath]</td>";
      echo "<td>$row[Bedrooms]</td>";
      //delete and edit button
      // Each row of table for listings
      echo "<td>";
        // Edit form
        echo "<form method='post' action='editProperties.php' style='display: inline; margin-right: 10px;'>";
          echo "<button type='submit' class='btn btn-primary'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
          <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/</svg></button>";
          // Hidden input to let the page know to edit
          echo "<input type='hidden' name='edit' value='edit'>";
          // Pass necessary data to edit page
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
          echo "<input type='hidden' name='address' value='{$row['Full_address']}'>";
        echo "</form>";
      echo "</td>";

    echo "</tr>";
    }
   ?> 
  </tbody>
</table>