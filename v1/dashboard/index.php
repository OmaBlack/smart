<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';
include '../include/functions/block.php';

$dayone = strtotime(date('01-m-Y')) ;
$now_time = strtotime("now");

//get user Informations
$app = new \Slim\App;



$app->get('/bank/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	echo dashboard($id);
  	R::close();

});

$app->get('/screener/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	echo screendashboard($id);
  	R::close();

});

$app->get('/regulator/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	echo regulatordashboard($id);
  	R::close();

});

$app->get('/screener/screened/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];

	return json_encode(ScreenedViewbags($id));
  	R::close();

});

$app->get('/banks/screened/{id}', function (Request $request, Response $response, array $args) {
	 $id = $args['id'];
	 
	 $sql = "SELECT `screening`.`Bag_id`, `Donor_id`, `Agent_Id`, `HBV`, `HCV`, `HIV`, `PVC`, `Test_date`, `Approved_by`, `Syphilis`, `Malaria`, `BloodType`, `Rhesus`, `ADDITIONAL` FROM `Bag` JOIN `screening` ON `Bag`.`Id`= `screening`.Bag_id JOIN `Request Screening` ON `Bag`.`Id`= `Request Screening`.`Bag_id` WHERE `Assigned_bloodBank` = '$id'";
	 
	 $bvs = R::getAll($sql);
	 
	 if ($bvs==null) {
	 	# code...
		return json_encode('You have no bags at the moment');
	 } else {
	 	# code...
		return json_encode($bvs);
	 }
	
  	R::close();

});

$app->get('/banks/pending/{id}', function (Request $request, Response $response, array $args) {
	 $id = $args['id'];
	 
	 $sql = "SELECT `Request Screening`.`Id` AS requestREf, `Screener_id`, `Donated_date`, `Donor_id`, `Request Screening`.`Status`, `Bag_id`,`Generated_Date` FROM `Request Screening` JOIN `Bag` ON `Bag`.`Id` = `Request Screening`.`Bag_id` WHERE `Request Screening`.`Status`= 'Pending' AND `Bag`.`Assigned_bloodBank` = '$id'";
	 
	 $bvs = R::getAll($sql);
	 
	 if ($bvs==null) {
	 	# code...
		return json_encode('You have no bags at the moment');
	 } else {
	 	# code...
		return json_encode($bvs);
	 }
	
  	R::close();

});


$app->get('/screener/pending/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];

	return json_encode(ScreenPendingbags($id));
  	R::close();

});

$app->get('/regulator/pending/{id}', function (Request $request, Response $response, array $args) {
	
	
	$bags = R::getAll("SELECT * FROM `screening` JOIN `Request Screening` ON `Request Screening`.`Bag_id` = `screening`.`Bag_id` JOIN `organizations` ON `organizations`.`Id` = `Screener_id` WHERE `Approved_by` = 0 ");

	if($bags == null){
		 $return = "You have no bags at the moment";
	}else{
		 $return =  $bags;
	}
	

	return json_encode($return);
  	R::close();

});

$app->post('/add', function (Request $request, Response $response){
		
		$user_id = $request->getParam('userID');
		$hospitalName = $request->getParam('hospitalName');
		$email = $request->getParam('email');
		$phone = $request->getParam('phone');
		$adresss = $request->getParam('adresss');
		$geo = $request->getParam('geo');
		$Prospect = $request->getParam('Prospect_comments');
		$state = $request->getParam('states');
		
 
	try {
		
		$pdo = "INSERT INTO `hospital`(`hospital_name`, `location`, `geo`, `fone`, `mail`, `note`,`created_by`,`created_date`) VALUES 									  ('$hospitalName','$adresss','$geo','$phone','$email','$Prospect','$user_id','$now_time')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
		$values =array("ok"=>true, "description"=>"successful", "New id"=>$id);
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});

function screendashboard($id)
{
	# code...
	$result[] = array(ScreenedBags($id),AwaitingScreening($id), destroyed($id));
	echo json_encode($result);
	
}

function regulatordashboard($id)
{
	# code...
	$result[] = array(Approval(),Approved($id), redestroyed($id));
	echo json_encode($result);
	
}

function Approval()
{
	# code...
	$count = R::getCell("SELECT COUNT(*) FROM `screening` WHERE `Approved_by` = '0'");
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function Approved($id)
{
	# code...
	$count = R::getCell("SELECT COUNT(*) FROM `screening` WHERE `Approved_by` = '$id'");
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function redestroyed($id)
{
	# code...
	
	$count = R::getCell("SELECT count(*) FROM `Bag_destroyed`");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function ScreenedBags($id)
{
	# code...
	
	$count = R::getCell("SELECT COUNT(*) FROM `screening`JOIN `Request Screening` on `Request Screening`.Bag_id = screening.Bag_id WHERE `Request Screening`.`Screener_id` = '$id'");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function AwaitingScreening($id)
{
	# code...
	
	$count = R::getCell("SELECT count(*) FROM `Request Screening` WHERE`Status`= 'Pending' AND `Screener_id` = '$id'");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function DestroyedBag($id)
{
	# code...
	
	$count = R::getCell("SELECT count(*) FROM `Bag_destroyed`");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function ScreenedViewbags($id)
{
	# code...	
		$bags = R::getAll("SELECT `screening`.`Bag_id`,`Screener_id`, `Donated_date`, `Donor_id`, `Status`,`Agent_Id`, `HBV`, `HCV`, `HIV`, `PVC`, `Test_date`, `Approved_by`, `Syphilis`, `Malaria`, `BloodType`, `Rhesus`, `ADDITIONAL` FROM `Request Screening` JOIN `screening` ON `Request Screening`.Bag_id = `screening`.Bag_id WHERE `Screener_id` = '$id' ");
	
		if($bags == null){
			return "You have no bags at the moment";
		}else{
			return $bags;
		}
}

function ScreenPendingbags($id)
{
	# code...	
		$bags = R::getAll("SELECT * FROM `Request Screening` WHERE `Status`= 'Pending' AND `Screener_id`  = '$id' ");
	
		if($bags == null){
			return "You have no bags at the moment";
		}else{
			return $bags;
		}
}


function dashboard($id)
{
	# code...
	$result[] = array(unused($id),awaiting($id), destroyed($id),expired($id),safe($id),unsafe($id),viewbags($id));
	echo json_encode($result);
	
}

function unused($id)
{
	# code...
	
	$count = R::getCell("SELECT count(*) as unused FROM `Bag` WHERE`Status`= 'Empty' AND `Assigned_bloodBank` = '$id'");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}
function awaiting($id)
{
	# code...
	$count = R::getCell("SELECT count(*) as awaiting FROM `Request Screening` JOIN `Bag` ON `Request Screening`.`Bag_id` = `Bag`.`Id` WHERE `Bag`.`Assigned_bloodBank` = '$id' AND `Request Screening`.`Status` = 'Pending' AND `Bag`.`Status` <> 'Empty'");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}
function destroyed($id)
{
	# code...
	
	$count = R::getCell("SELECT count(*) as destroyed FROM `Bag_destroyed` JOIN `Bag` ON `Bag_destroyed`.`Bag_id` = `Bag`.`Id` WHERE `Bag`.`Assigned_bloodBank` = '$id'");
	
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}
function expired($id)
{
	# code...
		$count = R::getCell("SELECT count(*) FROM `Bag` WHERE DATE(Generated_Date) < CURDATE() - INTERVAL 45 DAY AND `Bag`.`Assigned_bloodBank` ='$id'");
	
		if($count == null){
			return "0";
		}else{
			return $count;
		}
}

function safe($id)
{
	# code...
		$count = R::getCell("SELECT count(*) FROM `screening` JOIN `Bag` ON `screening`.`Bag_id` = `Bag`.`Id` WHERE (`HBV`= '0' AND `HCV`= '0' AND`HIV` = '0' AND `Syphilis`= '0')AND `Bag`.`Assigned_bloodBank` ='$id'");
	
		if($count == null){
			return "0";
		}else{
			return $count;
		}
}

function unsafe($id)
{
	# code...
		$count = R::getCell("SELECT count(*) FROM `screening` JOIN `Bag` ON `screening`.`Bag_id` = `Bag`.`Id` WHERE (`HBV`= '1' OR `HCV`= '1' OR`HIV` = '1' OR `Syphilis`= '1')AND `Bag`.`Assigned_bloodBank` ='$id'");
	
		if($count == null){
			return "0";
		}else{
			return $count;
		}
}

function viewbags($id)
{
	# code...	
		$bags = R::getAll("SELECT bag.`Id` as id, `Generated_Date`, `Assigned_bloodBank`, `Status`, `HBV`, `HCV`, `HIV`, `PVC`, `Test_date`, `Syphilis`, `Malaria`, `BloodType`, `Rhesus`FROM `Bag` JOIN screening ON screening.Bag_id = Bag.Id WHERE `Assigned_bloodBank` = '$id' ORDER BY `Bag`.`Status`  DESC");
	
		if($bags == null){
			return "You have no bags at the moment";
		}else{
			return $bags;
		}
}

$app->run();

