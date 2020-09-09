<?php
$db = [
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => 'patricia',
    'db' => 'snomedct_gex' //Cambiar al nombre de tu base de datos
];


function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8", $db['username'], $db['password']);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }
  
  
?>
