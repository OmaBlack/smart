<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';


$dayone = strtotime(date('01-m-Y')) ;
$now_time = strtotime("now");

//get user Informations
$app = new \Slim\App;



$app->get('/', function (Request $request, Response $response, array $args) {

	
	$sql = "SELECT * FROM `hospital`";
	
	$hospitals = R::getAll($sql);
	try {
		
		if($hospitals == null){
			$incorrect = "No Hospitals in the system.";
			return json_encode($incorrect);
		}else{
			return json_encode($hospitals);
		}
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});


$app->run();

