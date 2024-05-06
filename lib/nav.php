<?php
  require_once("initdb.php");
  require_once("funcs.php");
?>
<!--project scripts and styles for bootstrap/jquery-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" rel="stylesheet"/>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">Realestate Navigation</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <!--
          <li class="nav-item active">
            <a class="nav-link" href="/index.php">Home <span class="sr-only">(current)</span></a>
          </li>-->
          <li class="nav-item">
            <a class="nav-link" href="/listings/viewListings.php">View Listings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/listings/createListing.php">Post Listing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/visitScheduling/visits.php">Visit Scheduling</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/visitScheduling/scheduledVisits.php">Scheduled Visits</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/properties/viewProperties.php">View Properties</a>
          </li>
          <li class="nav-item">
              <div class="dropdown" style="margin-left:1vw">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add New Customer, Agent, or Property
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/newAgentCustomer/newAgent.php">New Agent</a>
                <a class="dropdown-item" href="/newAgentCustomer/newCustomer.php">New Customer</a>
                <a class="dropdown-item" href="/properties/newProperty.php">New Property</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
      </nav>