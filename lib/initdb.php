<?php
//getting my database from .env file to keep hidden
$env = parse_ini_file('.env');
$db_pass = $env["DB_PASS"];
$db_user="ajd99";
$db_server="sql2.njit.edu";
$db_name="ajd99";
$connection="";
try{
  $connection= mysqli_connect($db_server,$db_user,$db_pass,$db_name);
}catch(mysqli_sql_exception) {
  echo "Could not connect";
}

?>