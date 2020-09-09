<?php
$db_id = [
    'host' => '127.0.0.1',
    'username' => 'anonymous',
    'password' => 'usuario@mail.com',
	'db' => 'strSNS' ,
	'path' => 'FTPPublico/'
];


function connect2($db_id)
  {
      try {
		  $conn_id = ftp_connect($db_id['host']); 
		  $login_result = ftp_login($conn_id, $db_id['username'], $db_id['password']); 

          // $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8", $db['username'], $db['password']);
          // set the PDO error mode to exception
          // $conn_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn_id;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }
  
  
?>
