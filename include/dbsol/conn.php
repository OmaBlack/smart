<?php

require 'rb.php';

$dotenv = Dotenv\Dotenv::create(__DIR__,'../../.env_test');
$dotenv->load();

/**
* Description Class for Database connections 
*/

R::setup( 'mysql:host='.$_SERVER["SERVER_NAME"].';dbname='.$_ENV["dbname"].'',
      $_ENV["dbuser"],$_ENV["dbpass"] ); //for both mysql or mariaDB
/*	  
class DBConn 
{
	root
	private $conn;
	
	function __construct()
	{
		# code...
	}
	
	public function connect()
	{
		# 	code...
		$mysql_connect_str = "mysql:host=".$_SERVER["SERVER_NAME"].";dbname=".$_ENV["dbname"];

		$dbh = new PDO($mysql_connect_str, $_ENV["dbuser"], $_ENV["dbpass"]);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbh;
	}
}
*/
