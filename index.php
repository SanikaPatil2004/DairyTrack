<?php
  $servername = "localhost";
  $username = "root";
  $password ="Sanika@2004";
  $dbname ="milkdairy";
  $conn=mysqli_connect($servername,$username, $password, $dbname);
  
  if($conn)
  {
   echo "connection success";
  }
  else{
    echo "connection fail";
  }
  ?>