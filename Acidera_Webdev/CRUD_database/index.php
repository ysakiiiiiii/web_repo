<?php 

   $conn = new mysqli("localhost", "root", "root", "dbContacts"); 

   if($conn->connect_error){
      die("Connection Failed" . $conn->connect_error);
   }

   $sql = "CREATE DATABASE IF NOT EXIST dbContacts";
   $conn->query($sql);



?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Crud Database</title>
</head>
<body>
   <h1>Menu Links</h1>
   <div>
      <span>Add Record</span>
      <span>Update Record</span>
      <span>Delete Record</span>
   </div>

   <div id="root">

   </div>
   
</body>
</html>