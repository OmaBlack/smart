<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';
include '../../include/functions/block.php';


//get user Informations
$app = new \Slim\App;



$app->get('/moreinfo/{id}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	
	$pdo = "SELECT * FROM `screening` JOIN `Bag` on `screening`.`Bag_id` = `Bag`.`Id` JOIN `Request Screening` ON `Request Screening`.`Bag_id`= `Bag`.`Id` WHERE `screening`.`Bag_id` = '$id'";
	$bag = R::getAll($pdo);
	
	 R::close();
	
	if($bag == null){
		return "You have no bags at the moment";
	}else{
		return json_encode($bag);
	}

});

$app->post('/reject', function (Request $request, Response $response){
		
		$bagid = $request->getParam('rejectid');
		$Reason = $request->getParam('Reason');
		$createby = $request->getParam('createby');

	try {
		
		$pdo = "INSERT INTO `rejectBag`(`bagid`, `reason`, `agentid`) VALUES ('$bagid','$Reason','$createby')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		reject($bagid);
		
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});

$app->post('/destroy', function (Request $request, Response $response){
		
		$bagid = $request->getParam('rejectid');
		$Reason = $request->getParam('Reason');
		$createby = $request->getParam('createby');

	try {
		
		$pdo = "INSERT INTO `Bag_destroyed`(`Bag_id`, `reason`, `agentid`) VALUES ('$bagid','$Reason','$createby')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
		$fcn = 'logDestroyed';
		$args =  array("des"."$id", "$id","2020-06-16 00:00:00",  "$Reason", "Proof here", "$bagid");
		PushToBlock($fcn,$args);
		
		destroy($bagid);
		
		
		
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});

$app->post('/tested', function (Request $request, Response $response){
		
		$bagid = $request->getParam('bagid');
		$Bloodtype = $request->getParam('Bloodtype');
		$Rhesus = $request->getParam('Rhesus');
		$HBV = $request->getParam('HBV');
		$HCV = $request->getParam('HCV');
		$HIV = $request->getParam('HIV');
		$PCV = $request->getParam('PCV');
		$Malaria= $request->getParam('Malaria');
		$Syphilis = $request->getParam('Syphilis');
		$createby = $request->getParam('createby');

	try {
		
		$pdo = "INSERT INTO `screening`(`Agent_Id`, `HBV`, `HCV`, `HIV`, `PVC`, `Syphilis`, `Bag_id`, `Malaria`, `BloodType`, `Rhesus`) VALUES ('$createby','$HBV','$HCV','$HIV','$PCV','$Syphilis','$bagid','$Malaria','$Bloodtype','$Rhesus')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
		 $fcn = 'createScreening';
		$args =  array("screened"."$id", "$id","2020-06-16 00:00:00",  "$HBV", "$HCV", "$HIV", "$Syphilis","$Malaria","$PCV", "$Bloodtype", "$Rhesus", "0", "$createby","$bagid");
		
		PushToBlock($fcn,$args);
		
		tested($bagid);
		
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});


$app->post('/approved', function (Request $request, Response $response){
		
		$bagid = $request->getParam('bagid');
		$approve = $request->getParam('createby');

	try {
		
		$pdo = "UPDATE `screening` SET `Approved_by`='$approve' WHERE `Bag_id` = '$bagid'";
		
		R::exec($pdo);
		$values =array("ok"=>true, "description"=>"The Bag Has Been Approved");
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});

	

$app->post('/request/screening', function (Request $request, Response $response){
		
		$bagid= $request->getParam('bagid');
		$donorid = $request->getParam('donorid');
		$screener = $request->getParam('screener');
		$pending ='pending';
 
	try {
		
		
		$pdo = "INSERT INTO `Request Screening`( `Screener_id`, `Donor_id`, `Status`, `Bag_id`) VALUES ('$screener','$donorid','$pending','$bagid')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
	
		$fcn = 'createRequestScreening';
		$args =  array("reqscr"."$id", "$id", "$screener" ,"2020-06-16 00:00:00",  "$donorid", "$pending", "$bagid");
		PushToBlock($fcn,$args);
		
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});

function reject($id)
{
	# code...
	$pdo = "UPDATE `Request Screening` SET `Status`='Rejected' WHERE `Bag_id` ='$id' ";
	R::exec($pdo);
}

function destroy($id)
{
	# code...
	$pdo = "UPDATE `Request Screening` SET `Status`='Destroyed' WHERE `Bag_id` ='$id' ";
	R::exec($pdo);
}

function tested($id)
{
	# code...
	$pdo = "UPDATE `Request Screening` SET `Status`='Tested' WHERE `Bag_id` ='$id' ";
	R::exec($pdo);
}

$app->run();

