<?php
//getting my database from .env file to keep hidden
$env = parse_ini_file('.env');
$db_user=$env["DB_USER"];
$db_pass=$env["DB_PASS"];
$db_name=$env["DB_NAME"];
$db_server="sql2.njit.edu";
$connection="";
try{
  $connection= mysqli_connect($db_server,$db_user,$db_pass,$db_name);
}catch(mysqli_sql_exception) {
  echo "Could not connect";
}

?>