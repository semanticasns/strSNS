<?php
$db_str = [
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => 'patricia',
    'db' => 'strsns' //Cambiar al nombre de tu base de datos
];


function connect_str($db_str)
  {
      try {
          $conn = new PDO("mysql:host={$db_str['host']};dbname={$db_str['db']};charset=utf8", $db_str['username'], $db_str['password']);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }

?>
