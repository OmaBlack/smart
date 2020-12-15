<?	

	function ongoingCount($id,$conn)
	{
		try {
			# code...
					//Run a sql to select the user from database
					$stmt = $conn->prepare("SELECT COUNT(*) as OngoingTotal FROM `orders` WHERE `order_by` = ? AND `order_state`<> 'Completed' AND `order_state`<> 'Declined'");
					
					$stmt->execute([$id]); 
					$ongoing = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
					$onGoings = $stmt->fetch();
					
					//count numbers of roll 
					$count = $stmt->rowCount();
					
					//verify the password is correct
					if($count == 1){
						
						return $onGoings;
						
					}else{
						$incorrect = array("status"=>"error", "message"=>"Login,Internal Error Please try again.");
						return $incorrect;
					}
		} catch (PDOException $e) {
			$failed = array("status"=>"error", "message"=>"An error had occurred.");
			return $failed;
		}
	}
	
	function ongoing($id,$conn)
	{
		try {
			# code...
					//Run a sql to select the user from database
					$stmt = $conn->prepare("SELECT * FROM `orders` WHERE `order_by` = ? AND `order_state`<> 'Completed' AND `order_state`<> 'Declined' ORDER BY `order_id` DESC LIMIT 4");
					
					$stmt->execute([$id]); 
					$ongoing = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
					$onGoings = $stmt->fetch();
					
					//count numbers of roll 
					$count = $stmt->rowCount();
					
					//verify the password is correct
					if($count >= 1){
						
						return $onGoings;
						
					}else{
						$incorrect = array("status"=>"true", "message"=>"No on going order.");
						return $incorrect;
					}
		} catch (PDOException $e) {
			$failed = array("status"=>"error", "message"=>"An error had occurred.");
			return $failed;
		}
	}
	
	
	function totalOrder($id,$conn)
	{
		try {
			# code...
					//Run a sql to select the user from database
					$stmt = $conn->prepare("SELECT COUNT(*) as TotalOrder FROM `orders` WHERE `order_by` = ? ");
					
					$stmt->execute([$id]); 
					$ongoing = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
					$onGoings = $stmt->fetch();
					
					//count numbers of roll 
					$count = $stmt->rowCount();
					
					//verify the password is correct
					if($count == 1){
						
						return $onGoings;
						
					}else{
						$incorrect = array("status"=>"error", "message"=>"Login,Internal Error Please try again.");
						return $incorrect;
					}
		} catch (PDOException $e) {
			$failed = array("status"=>"error", "message"=>"An error had occurred.");
			return $failed;
		}
	}
	
	
	function totalDelivery($id,$conn)
	{
		try {
			# code...
					//Run a sql to select the user from database
					$stmt = $conn->prepare("SELECT COUNT(*) as TotalDelivery FROM `orders` WHERE `order_by` = ? AND `order_state`= 'Completed'");
					
					$stmt->execute([$id]); 
					$ongoing = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
					$onGoings = $stmt->fetch();
					
					//count numbers of roll 
					$count = $stmt->rowCount();
					
					//verify the password is correct
					if($count == 1){
						
						return $onGoings;
						
					}else{
						$incorrect = array("status"=>"error", "message"=>"Login,Internal Error Please try again.");
						return $incorrect;
					}
		} catch (PDOException $e) {
			$failed = array("status"=>"error", "message"=>"An error had occurred.");
			return $failed;
		}
	}
?>