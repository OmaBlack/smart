<?

//require '../include/dbsol/conn.php';

function o2stock()
{
	# code...
	
	$o2 = R::findAll( 'oxygen' );
	
	if($o2 == null){
		return "No Stock Found!";
	}else{
		return $o2;
	}
}

function stock()
{
	# code...
	$qury = "SELECT `s_group`, SUM(`s_no`) as Total_unit, MIN(`cost`) as Lowest , Max(`cost`) as highest FROM `Stock` where `cost` > 0 group By `s_group`";
	$beans = R::getAll( $qury);
	
	if($beans == null){
		return "No Stock Found!";
	}else{
		return $beans;
	}
}


function Labx()
{
	# code...
	$count = R::count('orders', 'order_pick=?' , [""]);
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function ongoing()
{
	# code...
	$count = R::count('orders', 'order_state=?' , ["Awaiting Pick Up"]);
	if($count == null){
		return "0";
	}else{
		return $count;
	}
}

function followup()
{
	$now25 = strtotime("now") - (25 * 60);
	 
	$follow = R::findAll('orders', 'order_state=?' , ["Awaiting Pick Up"]);
	if($follow == null){
		return "0";
	}else{
		$i = 0;
		foreach($follow as $row){
			if( $now25 >= $row["tym"] ){
				$i++;
			}
		}
		return $i;
	}
}

function sumUpBlood()
{  
	
	$follow = R::getAll( 'SELECT sum(s_no) as ti FROM Stock' );
	if($follow == null){
		return "0";
	}else{
		return $follow[0]["ti"];
	}
}

function sumUpOxygen()
{
	$follow = R::getAll( 'SELECT sum(qty) as ti FROM oxygen' );
	if($follow == null){
		return "0";
	}else{
		return $follow[0]["ti"];
	}
}

function dispatch()
{
	
	$dispatch = R::count('dispatch', 'status=?' , ["Free"]);
	
	if($dispatch == null){
		return "0";
	}else{
		return $dispatch;
	}
	
}

function agent()
{
	
	$dispatch = R::count('orders', 'agent=?' , [$_SESSION['id']]);
	
	if($dispatch == null){
		return "0";
	}else{
		return $dispatch;
	}
	
}
	
?>