<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require '../../vendor/autoload.php';
require '../../include/dbsol/conn.php';
include '../../include/utlites/cors.php';
include '../../include/functions/block.php';



//get user Informations
$app = new \Slim\App;


$app->get('/', function (Request $request, Response $response, array $args) {
	
	try {
		$values =array("ok"=>true, "description"=>"This is a set of credentials used to authenticate a user", "method allowed"=>"post");
		  
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
});

$app->post('/', function (Request $request, Response $response){
	
	 	$user_id = $request->getParam('email');
 	   	$pwd = $request->getParam('pwd');
		$org = $request->getParam('org');
	
	try {
		
		$pdo = "SELECT User.`Id` as id, `first_name`, `last_name`, User.`Phone`, User.`email`, `privilege`, `password`, `salt`, User.`status` as status, `organizationsid`,`OrganizationsName`, `Category`,`Address`, `city`, `longitude`,` latitude`, organizations.`Status` as orgStatus, `State`, `Country` FROM `User` RIGHT JOIN organizations ON User.organizationsid = organizations.Id WHERE User.`email` = '$user_id'
";
		
		$login = R::getRow($pdo);
		if($login == null){
			
			$error ='{"ok":false,"description":"Please enter a valid user!"}';
			return ($error);
		}else{
			$salt= $login['salt'];
			
			$password = hash('sha512', $salt . $pwd);
			if($org = $login['organizationsid']){
				if ($password  === $login['password']) {
					//ValidChecker($login['orgStatus'],$login['status']);
					$values =array("ok"=>true, "description"=>$login);
					return json_encode($values);
				}else{
					
					$error ='{"ok":false,"description":"Incorrect Password!"}';
					return ($error);
				}
			}else{
				$error ='{"ok":false,"description":"Please enter a valid Organizations Id!"}';
				return ($error);
			}
		}
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});


$app->post('/add/user', function (Request $request, Response $response){
		
		$firstname= $request->getParam('firstname');
		$lastname = $request->getParam('lastname');
		$phone = $request->getParam('phone');
		$email = $request->getParam('email');
		$privilege = $request->getParam('privilege');
		$org = $request->getParam('org');
		$created = $request->getParam('createby');
		$pending ='pending';
		
		$password = hash('sha512', '1234' . 'password');
 
	try {
		
		$pdo = "INSERT INTO `User`(`first_name`, `last_name`, `Phone`, `email`, `privilege`, `status`, `organizationsid`, `createby`, `password`, `salt`)  VALUES ('$firstname','$lastname','$phone','$email','$privilege','$pending','$org','$created','$password','1234')";
		
		R::exec($pdo);
		$id = R::getInsertID();
		
		$fcn = 'createUser';
		
		$args =  array("usr"."$id", "$id","$firstname", "$lastname",  "$phone", "$email", "$privilege", "$pending", "$$org");
		PushToBlock($fcn,$args);
		
		$values =array("ok"=>true, "description"=>"user created,user has been sent an email with the details. ");
		return json_encode($values);
		
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
  
   R::close();

});


$app->post('/edit/user', function (Request $request, Response $response){
	$createid = $request->getParam('createid');

try {
	
    $sql = "SELECT * FROM `User` WHERE `Id`= '$createid'";
    $bvs = R::getAll($sql);
    foreach($bvs as $b){  $user = $b; }
	
	$firstname= (empty($request->getParam('fname'))) ? $user['first_name'] : $request->getParam('fname');
	$lastname = (empty($request->getParam('lname'))) ? $user['last_name'] : $request->getParam('lname');
	$phone = (empty($request->getParam('lname'))) ? $user['last_name'] : $request->getParam('lname');
	

	$pdo = "UPDATE `User` SET `first_name`='$firstname',`last_name`='$lastname',`Phone`='$phone' WHERE `Id`= '$createid'";
	
	R::exec($pdo);
			
	//$fcn = 'createUser';
	
	//$args =  array("usr"."$id", "$id","$firstname", "$lastname",  "$phone", "$email", "$privilege", "$pending", "$$org");
	//PushToBlock($fcn,$args);
	
	$values =array("ok"=>true, "description"=>"user profile edit. ");
	return json_encode($values);
	
} catch (Exception $e) {
	$error = "system Failure!".$e;
	return json_encode($error);
}

R::close();

});

$app->post('/admin', function (Request $request, Response $response){
	
 	$user_id = $request->getParam('email');
   	$pwd = $request->getParam('pwd');
	
	$encPWD = md5($pwd) ;
	
	$pdo = "SELECT * FROM `admin` WHERE `email` = '$user_id'";
	
	$login = R::getRow($pdo); 
	
	if($login == null){
		$error ='{"ok":false,"description":"Please enter a valid user!"}';
		return ($error);
	}else{
		if ($encPWD  === $login['pwd']) {
			$values =array("ok"=>true, "description"=>$login);
			return json_encode($values);
		}else{
			$error ='{"ok":false,"description":"Incorrect Password!"}';
			return json_encode($error);
		}
	}
});

function ValidChecker($org,$usr)
{
	# code...
	if($usr !== 'Live' || $org !=='Approve'){
		
		$stop ='{"ok":false,"error_code":501,"description":"This account is not allowed to continued"}';
		exit($stop);
	}
}

$app->run();

