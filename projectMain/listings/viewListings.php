<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');

//delete functionality
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
<link rel="stylesheet" href="./style.css">
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
    $query = "SELECT * FROM LISTING ORDER BY LIST_DATE DESC";
    $result = mysqli_query($connection,$query);
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
      echo "<td>$agentName[Name] *****".substr($row["Agent_SSN"],5)."</td>";

      echo "<td>$" . number_format($row['asking_price'], 2, '.', ',') . "</td>";
      //delete and edit button
      
      // Each row of table for listings
      echo "<td>";
        // Edit form
        echo "<form method='post' action='edit_listing.php' style='display: inline; margin-right: 10px;'>";
          echo "<button type='submit' class='btn btn-primary'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
          <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/</svg></button>";
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