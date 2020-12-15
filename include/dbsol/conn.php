<?php

require 'rb.php';

$dotenv = Dotenv\Dotenv::create(__DIR__,'../../.env_test');
$dotenv->load();

/**
* Description Class for Database connections 
*/

R::setup( 'mysql:host='.$_SERVER["SERVER_NAME"].';dbname='.$_ENV["dbname"].'',
      $_ENV["dbuser"],$_ENV["dbpass"] ); //for both mysql or mariaDB
