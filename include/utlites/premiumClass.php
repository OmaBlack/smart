<?php
	function premiumClass($premium, $blood)
	{
		# code...
		if (strpos($blood, '-') !== false) {
		    if($premium == 1 ){
				$cost= 4000;
			}elseif($premium == 2){
				$cost= 3000;
			}
			elseif($premium == 0){
					$cost= 3000;
			}
		}
		elseif(strpos($blood, '+') !== false) {
			if($premium == 1 ){
				$cost= 2500;
			}elseif($premium == 2){
				$cost= 2000;
			}
			elseif($premium == 0){
					$cost= 1000;
			}
		}
		return $cost;
	}
	
	function pricing($hospital, $blood)
	{
		# code..
		//$pdo = "SELECT `premium` FROM `secure_login` WHERE `memberid` = '$hospital'";
		//$pricing = R::getAll($pdo);
		
		 $pricing  = R::findOne( 'secure_login', ' memberid = ? ', [ $hospital ] );
		
		if($pricing == null){
		
			if (strpos($blood, '-') !== false) {
				$cost= 4000;
			}
			elseif(strpos($blood, '+') !== false) {
				$cost= 3000;
			}
			return $cost;
		}else{
			
			$premium = $pricing['premium'];
			
			if (strpos($blood, '-') !== false) {
				if($premium == 11 ){
					$cost= 22000;
				}
				elseif($premium == 1){
						$cost= 21000;
				}
				elseif($premium == 2){
						$cost= 20000;
				}
				elseif($premium == 0){
						$cost= 19000;
				}
				elseif($premium == 3){
						$cost= 18000;
				}
			}
			elseif(strpos($blood, '+') !== false) {
				if($premium == 11 ){
					$cost= 18000;
				}
				elseif($premium == 1){
						$cost= 17000;
				}
				elseif($premium == 2){
						$cost= 16000;
				}
				elseif($premium == 0){
						$cost= 15000;
				}
				elseif($premium == 3){
						$cost= 14000;
				}
			}
			return $cost;
		}
		
	}
?>