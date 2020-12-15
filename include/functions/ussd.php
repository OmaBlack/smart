<?

//require '../include/dbsol/conn.php';

function callbacK()
{
	# code...
	
	//$back = R::findAll( 'ussd_callback' );
	$back  = R::findAll( 'ussd_callback', ' feedback  = ? ', [ '0' ] );
	  
	if($back == null){
		return "No Call-BacK Found!";
	}else{
		return $back;
	}
}

function Blood()
{
	# code...
	
	$blood = R::findAll( 'ussd_Blood' );
	
	if($blood == null){
		return "No USSD Blood order Found!";
	}else{
		return $blood;
	}
}

function oxygen()
{
	# code...
	
	//SELECT u.* , o.size FROM ussd_Oxygen u LEFT JOIN oxygen o on o.id = u.product
	
	$blood = R::getAll( 'SELECT u.* , o.size FROM ussd_Oxygen u LEFT JOIN oxygen o on o.id = u.product' );//R::findAll( 'ussd_Oxygen' );
	
	if($blood == null){
		return "No USSD Oxygen order Found!";
	}else{
		return $blood;
	}
}




?>