<?php
$db_drug = [
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => 'patricia',
    'db' => 'snomedct_drug' //Cambiar al nombre de tu base de datos
];


function connect_drug($db_drug)
  {
      try {
          $conn = new PDO("mysql:host={$db_drug['host']};dbname={$db_drug['db']};charset=utf8", $db_drug['username'], $db_drug['password']);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }

?>
