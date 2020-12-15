<?

function AddToBoat($orderid,$name,$medical,$type,$unit,$hospital,$doc,$date,$city,$cost)
{
	# code...
	$hospitala = getHospitalNaMe($hospital);
	$city = getHospitalCity($hospital);
	

	$ref_id = getBoatCFP();
	
	
	$pdo = "INSERT INTO `boat_story`(`orderid`, `name`, `medical_condition`, `type`, `units`, `Hospital`, `Doctor`,  `Date`, `ref_id`, `status`, `cost`,`city`) VALUES ('$orderid','$name','$medical','$type','$unit','$hospitala','$doc','$date','$ref_id','66','$cost','$city')";
		
	R::exec($pdo);
}



function getHospitalNaMe($orderid){
	
	$cgf = R::findOne('user_info', 'ref_id=?' , [$orderid]);
	
	if($cgf == null){
		$error = "No know establishment location!";
		return $error;
	}else{

		$name = $cgf['name'];
		
		return $name;
		
	}
}

function getHospitalCity($orderid){
	
	$cgfas= R::findOne('user_info', 'ref_id=?' , [$orderid]);
	
	if($cgfas == null){
		$error = "No know establishment location!";
		return $error;
	}else{

		$city = $cgfas['city'];
		
		return $city;
		
	}
}

function getBoatCFP(){
	$pdo = "SELECT a.usr_id, a.amount - SUM(b.cost) as Difference FROM bloodtrust a INNER JOIN boat_story b ON a.usr_id=b.ref_id GROUP BY b.ref_id ORDER BY RAND()";
	$cfp = R::getAll($pdo);
	
	try {
		if($cfp == null){
			$incorrect = "0";
			return json_encode($incorrect);
		}else{
			$incorrect = "66";
			return json_encode($incorrect);
		}
		
	} catch (Exception $e) {
		
	}
	
	
	
}