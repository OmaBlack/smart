<?
function finddispatch($city)
{
	# code...
	try {
		$dispatch = R::findAll('dispatch', 'status=? AND State=?' , ['Free',$city]);
		if($dispatch == null){
			$error = "Please enter a valid user!";
			return $error;
		}else{
			
			return $dispatch;
		}
	
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
}

function dispatchHospitalNumber($hospital)
{
	# code...
	try {
		
		$phoneNumber = R::findOne('user_info', 'ref_id=?' , [$hospital]);
		if($phoneNumber == null){
			$error = "No Phone number assigned!";
			return $error;
		}else{
			
			return $phoneNumber['fone'];
		}
	
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
}	

function SuggestBloodBank($hospital_id,$blood)
{
	# code...
	try {
		
		$locations = R::findOne('user_info', 'ref_id=?' , [$hospital_id]);
		if($locations == null){
			$error ="No vaild hospital";
			return $error;
		}else{
			$address = $locations['address'];
			$state = $locations['state'];
			$geo_loc = $locations['geo_loc'];
			$banks_loc = bloodBanks($state,$blood);
			
			
			if ($geo_loc =='' || $geo_loc == null ) {
				# code...
				$geo_loc = getGeoLocation($address,$hospital_id);
				
				$result = sortClosest($geo_loc,$banks_loc);
			} else {
				# code...
				$result = sortClosest($geo_loc,$banks_loc);
			}
		}
		return $result;
	
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
}


function getGeoLocation($address,$hospital_id)
{
	// url encode the address
	    $address = urlencode($address);
     
	    // google map geocode api url
	    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAg7-YYR9clrIdYVCILJ0zZvL6qDquZLYw";
 
	    // get the json response
	    $resp_json = file_get_contents($url);
     
	    // decode the json
	    $resp = json_decode($resp_json, true);
 
	    // response status will be 'OK', if able to geocode given address 
	    if($resp['status']=='OK'){
 
	        // get the important data
	        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
	        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
	        
         
	        // verify if data is complete
	        if($lati && $longi){
				$geo_loca = $lati.','. $longi;
				SaveGeo($hospital_id,$geo_loca);
				
				return $geo_loca;
             
	        }else{
	            return false;
	        }
         
	    } 
 
	    else{
	        echo "<strong>ERROR: {$resp['status']}</strong>";
	        return false;
	    }
}

function SaveGeo($hospital_id,$geo){
	
	try {	
		
		$pdo = "UPDATE `user_info` SET `geo_loc`='$geo'  WHERE `ref_id` = '$hospital_id'";
			
		R::exec($pdo);
		
		return json_encode($geo);
		
	}  catch (RedBeanPHP\RedException\SQL $e) {
    echo $e->getMessage();
	}
}

function sortClosest($baseLocation,$locations){
	
	
	$base_location = explode(',', $baseLocation); 
	
	$distances = array();
	
	foreach ($locations as $value)
	{
	 $longitude1 = $base_location[1];
	 $longitude2 = $value['longi'];
	 $latitude1 = $base_location[0];
	 $latitude2 = $value['lat'];

	 
		 
 	 $theta = $longitude1 - $longitude2;
      $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

      $distance = acos($distance); 
      $distance = rad2deg($distance); 
      $distance = $distance * 60 * 1.1515;
	  $distance = $distance * 1.609344; 
	  
	  $single = array("ref_id"=>$value['ref_id'],"name"=>$value['name'],"base_loc"=>$value['longi'].','.$value['lat'], "distance"=>$distance);
	  $distances[] =  $single;
	}
	
	$columns = array_column($distances, 'distance');
	
	array_multisort($columns, SORT_ASC, $distances);
	return $distances; 

}


function bloodBanks($city,$blood){
	$qty = 0;
	$s_group = $blood;
	
	$banks = array();
	
	try {
		$pdo = "SELECT user_info.ref_id , user_info.name ,user_info.geo_loc FROM `Stock` LEFT JOIN user_info ON `Stock`.`ref_id`= `user_info`.`ref_id` WHERE `s_group` = '$s_group'AND `s_no`> '$qty' AND `state`= '$city'";
		
		$prechecker = R::getAll($pdo);
		
		if($prechecker == null){
			$incorrect = "No Match available.";
			return json_encode($incorrect);
		}else{
			foreach ($prechecker as $value)
			{	
				$geo_loc = $value['geo_loc'];
				
				if ($geo_loc =='' || $geo_loc == null ) {
					 
					$ref_id = $value['ref_id'];
					$name = $value['name'];
					$single= array("ref_id"=>$ref_id, "name"=>$name, "lat"=>0, "longi"=>0);
					$banks[] =  $single;
					
				}else{
					$bank_location = explode(',', $value['geo_loc']); 
					$ref_id = $value['ref_id'];
					$name = $value['name'];
					$lat = $bank_location[0];
					$longi = $bank_location[1];
				
					$single= array("ref_id"=>$ref_id, "name"=>$name, "lat"=>$lat, "longi"=>$longi);
					$banks[] =  $single;
					//return $single;
				}
			}
			return $banks;
		}
		
	} catch (Exception $e) {
		
	}
	
}



function SuggestDispatch($bankId)
{
	# code...
	try {
		
		$locations = R::findOne('user_info', 'ref_id=?' , [$bankId]);
		if($locations == null){
			$error = "No know dispatch location!";
			return $error;
		}else{
			$address = $locations['address'];
			$state = $locations['state'];
			$geo_loc = $locations['geo_loc'];
			$dispatch_loc = finddispatch($state);
			
			
			if ($geo_loc =='' || $geo_loc == null ) {
				# code...
				$geo_loc = getGeoLocation($address,$hospital_id);
				
				$result = sortClosestDispatch($geo_loc,$dispatch_loc);
			} else {
				# code...
				$result = sortClosestDispatch($geo_loc,$dispatch_loc);
			}
		}
		return $result;
	
	} catch (Exception $e) {
		$error = "system Failure!".$e;
		return json_encode($error);
	}
	
}

function sortClosestDispatch($base_location,$locations)
{
	
	$base_location = explode(',', $baseLocation); 
	
	$distances = array();
	
	foreach ($locations as $value)
	{
	 $dispatch_location = explode(',', $value['loca']);  
		
	 $longitude1 = $base_location[1] ;
	 $longitude2 = $dispatch_location[1];
	 $latitude1 = $base_location[0];
	 $latitude2 =  $dispatch_location[0] ;

	 
		 
 	 $theta = $longitude1 - $longitude2;
      $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

      $distance = acos($distance); 
      $distance = rad2deg($distance); 
      $distance = $distance * 60 * 1.1515;
	  $distance = $distance * 1.609344; 
	  
	  $single = array("dispatch_id"=>$value['dispatch_id'],"dispatch_name"=>$value['dispatch_name'],"status"=>$value['status'],"ba3"=>$value['ba3'],"loca"=>$value['loca'], "time"=>$value['time'],"State"=>$value['State'], "distance"=>$distance);
	  $distances[] =  $single;
	}
	
	$columns = array_column($distances, 'distance');
	
	array_multisort($columns, SORT_ASC, $distances);
	return $distances; 
	
}

function getdetailses($valuePassed){
	
	$cgf = R::findOne('user_info', 'ref_id=?' , [$valuePassed]);
	
	if($cgf == null){
		$error = "No know establishment location!";
		return $error;
	}else{
		$addresses = $cgf['address'];
		$geo_loces = $cgf['geo_loc'];
		$base_locationes = explode(',', $geo_loces); 
		
		$return = array("address"=>$addresses, "lat"=>$base_locationes[0], "long"=>$base_locationes[1]);
		return $return;
		
	}
}