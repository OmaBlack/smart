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



$app->get('/}', function (Request $request, Response $response, array $args) {
	$id = $args['id'];
	
   R::close();

});

$app->post('/transfer', function (Request $request, Response $response){
	
	$BagID = $request->getParam('BagtransID');
	$bbid = $request->getParam('bbid');
	$org = $request->getParam('orgid');
	$createid = $request->getParam('createid');
	
	
	try {
		
		$pdo = "INSERT INTO `transferbag`(`from_bloodbank`, `to_bloodbank`, `agentid`, `bagid`) VALUES ('$bbid','$org','$createid','$BagID')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		updatetransfer($BagID,$bbid);
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
		return json_encode($values);
		
	}catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
   
   
    R::close();
 });

$app->post('/request', function (Request $request, Response $response){
		
		$number = $request->getParam('number');
		$usr = $request->getParam('user');
		$org = $request->getParam('org');
		
		
 
	try {
		
		$pdo = "INSERT INTO `requestbag`(`qty`, `userid`, `orgid`) VALUES ('$number','$usr','$org')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
		$values =array("ok"=>true, "description"=>"Your request was successful, References Id: ".$id);
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

function updatetransfer($bag,$newOwner)
{
	# code...
	$pdo = "UPDATE `Bag` SET `Assigned_bloodBank`='$newOwner' WHERE `Id`='$bag'";
	R::exec($pdo);
}
$app->run();

