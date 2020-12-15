<?


function orderCheck($Values)
{
	$fres= mysqli_query($conn, "SELECT * FROM `Stock` WHERE `id` = '$Values'"); 
	$count = mysqli_num_rows($fres);
	if($count >= 1) {
		return false;
	}else{
		return true;
	}
	
}
	
function costingNow($labid,$uid,$blood,$conn)
{
	$fres= mysqli_query($conn, "SELECT cost FROM `Stock` WHERE `id` = '$labid'"); 
	$count = mysqli_num_rows($fres);
	
	if($count >= 1) {
		while($row = mysqli_fetch_assoc($fres)) {
		  	$priceing = $row['cost'];
		
		   $pre= mysqli_query($conn, "SELECT premium FROM `secure_login` WHERE `memberid` = '$uid'"); 
		    $rows = mysqli_fetch_assoc($pre);
			$premium = $row['premium'];
		
			$markup = premiumClass($premium,$blood);
			$price = $priceing + $markup;
			return $price;
		}
	}
}

function SuggestBloodBank($hospital_id,$blood,$conn)
{
	$fres= mysqli_query($conn, "SELECT address,geo_loc,state FROM `user_info` WHERE `ref_id` = '$labid'"); 
	$count = mysqli_num_rows($fres);
	
	if($count >= 1) {
		while($row = mysqli_fetch_assoc($fres)) {
			$address = $row['address'];
			$state = $row['state'];
			$geo_loc = $row['geo_loc'];
			
			if ($geo_loc =='' || $geo_loc == null ) {
				# code...
				echo "no geo loca";
			} else {
				# code...
				echo "geo avaiable";
			}
		
		}
	}
	
}

?>