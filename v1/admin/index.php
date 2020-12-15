<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';


//get user Informations
$app = new \Slim\App;



$app->get('/', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	 
	echo $id;
});

$app->get('/getRequests', function (Request $request, Response $response, array $args) {
	try {
		
		$pdo = "SELECT * FROM `requestbag` WHERE treated = 0";
		$bagRequest = R::getAll($pdo);
		
		if($bagRequest == null){
			return "You have no bags at the moment";
		}else{
			return json_encode($bagRequest);
		}
		
	}
	catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
   R::close();

});

$app->get('/getOrg', function (Request $request, Response $response) {	
	try {
		
		$pdo = "SELECT `organizationsid`,`OrganizationsName`, `Category`,`Address`, `city`, `longitude`,` latitude`, organizations.`Status` as orgStatus, `State`, `Country` FROM `User` RIGHT JOIN organizations ON User.organizationsid = organizations.Id";
		$getOrg = R::getAll($pdo);
		
		if($getOrg == null){
			return "No Organizations avaiable";
		}else{
			return json_encode($getOrg);
		}
		
	}
	catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
   R::close();

});

$app->get('/getBags', function (Request $request, Response $response) {	
	try {
		
		$pdo = "SELECT requestbag.id,requestdate,qty,OrganizationsName FROM `requestbag` JOIN organizations ON requestbag.orgid = organizations.id WHERE `treated` = 0";
		$getOrg = R::getAll($pdo);
		
		if($getOrg == null){
			return "No Organizations avaiable";
		}else{
			return json_encode($getOrg);
		}
		
	}
	catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
   R::close();

});

$app->post('/generateBags', function (Request $request, Response $response) {	
	try {
		
		$GenId = $request->getParam('reqID');
		
		$pdo = "SELECT * FROM `requestbag` WHERE `id` = '$GenId' and `treated` = 0";
		$bagRequest = R::getRow($pdo);
		
		if($bagRequest == null){
			return "The bags has been generated already";
		}else{
			$qty = $bagRequest['qty'];
			$orgid = $bagRequest['orgid'];
			$x = 1;
			
			while($x <= $qty) {
				$bag = generateCode($orgid);
			  echo "The number is: ".$bag."<br>";
			  instaGen($bag,$orgid);
			  
			  $x++;
			}	
			completedGen($GenId);
		}
		
	}
	catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
   R::close();

});


	
function generateCode($org){
	# code...
	return  $org . substr(str_shuffle("0123456789"), 0, 5);
}

function verifyCode($bag,$org){
	# code...
	$pdo = "SELECT * FROM `Bag` WHERE Id` = '$bag' and `treated` = 0";
	$verifyCode = R::getRow($pdo);
	
	if($verifyCode == null){
		return $bag;
	}else{
		generateCode($org);
	}
}

function instaGen($bag,$org){
	$d=strtotime("now");
	$date = date("Y-m-d h:i:s", $d);
	$pdo = "INSERT INTO `Bag`(`Id`,`Assigned_bloodBank`, `Generated_Date`, `Status`) VALUES ('$bag','$org','$date','Empty')";
	R::exec($pdo);
}

function completedGen($GenId){
	
	$pdo = "UPDATE `requestbag` SET `treated` = 1 WHERE `id` = '$GenId' ";
	R::exec($pdo);
}

$app->run();

