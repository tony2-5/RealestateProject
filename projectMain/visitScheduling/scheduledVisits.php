<?php
#tab for scheduled visits, can search for visits on certain date, with certain agent
include_once("../../lib/nav.php");
?>
  <link rel="stylesheet" href="./style.css">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
      <th scope="col">Name</th>
      <th scope="col">Address</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
      <td class="dropdownTD"> 
         <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               Dropdown button
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
               <a class="dropdown-item" href="#">Action</a>
               <a class="dropdown-item" href="#">Another action</a>
               <a class="dropdown-item" href="#">Something else here</a>
            </div>
         </div>
      </td>
    </tr>
  </tbody>
</table>