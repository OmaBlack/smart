<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';
include '../../include/functions/dashboard.php';

$dayone = strtotime(date('01-m-Y')) ;
$now_time = strtotime("now");

//get user Informations
$app = new \Slim\App;


$app->get('/', function (Request $request, Response $response, array $args) {
	
	try {
		//$delivered = R::findAll('orders', 'closed=?' , ['opens']);
		$qury = "SELECT o.* ,u.name, d.dispatch_name FROM orders o, dispatch d, user_info u WHERE o.order_by = u.ref_id AND o.dispatch = d.dispatch_id AND `closed` = 'opens' AND o.order_state = 'Completed'";
		
		$delivered = R::getAll( $qury);
		
		//$delivered = R::find( 'orders', ' closed LIKE ? ', [ 'opens' ] );
		if($delivered == null){
			$error = "No open order";
			return json_encode($error);
		}else{
			
			return json_encode($delivered);
		}
	
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
    R::close();
	
});

$app->post('/returned', function (Request $request, Response $response){
	
	$order_id = $request->getParam('order_id');
	$return_duration =$request->getParam('return_duration');
	$return_qty =$request->getParam('return_qty');
	$return_tym =strtotime("now");
	$return = 'returned';
	
	try {
		$pdo = "UPDATE `orders` SET `closed`='returned' WHERE `order_id` = '$order_id'";
		$returned == R::exec($pdo);
		
		return json_encode($return);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
    R::close();
	
});

$app->post('/closed', function (Request $request, Response $response){
	$order_id = $request->getParam('order_id');
	$close = 'closed';
	
	try {
		$pdo = "UPDATE `orders` SET `closed`='closed' WHERE `order_id` = '$order_id'";
		$returned == R::exec($pdo);
		
		return json_encode($close);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
    R::close();
	
});

$app->run();

