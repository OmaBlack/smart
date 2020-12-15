<? 

function ClassFunction($value)
{
	# code...
	
	if($value== 1 ){
		return $type = "Platinum";
	}elseif($value == 2){
		return $type = "Gold";
	}
	elseif($value == 0){
	 	return $type = "Silver";
	}
	

}

function oxygenCanisterFunction()
{
	$xygen = R::findAll( 'oxygen' );
	
	$rows .="<option value=''> Select a Canister</option>";

	foreach ($xygen as $rw){
		$rows .="<option value='".$rw["id"]."'>".$rw["size"]."</option>";
	}
	
	return $rows;
}