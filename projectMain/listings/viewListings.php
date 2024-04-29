<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');
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

      echo "<td>$row[asking_price]</td>";
      //delete button
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
          $ssn=encrypt($encryptionKey,$row["Agent_SSN"]);
          echo "<input type='hidden' name='ssn' value=$ssn>";
          echo "<input type='hidden' name='date' value=$row[list_date]>";
          echo "<input type='hidden' name='time' value=$row[asking_price]>";
          echo "<input type='hidden' name='address' value='$row[Full_address]'>";
        echo "</div>";
        echo "</form>";
      echo "</td>";
    echo "</tr>";
    }
   ?> 
  </tbody>
  
  
</table>
