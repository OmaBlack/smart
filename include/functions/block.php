<?php 

	function PushToBlock($fcn,$args){
		# code...	
		$url = 'http://localhost:4000/channels/mychannel/chaincodes/fabcar';
		$authorization = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1OTI1MDQ0NDgsInVzZXJuYW1lIjoiYmxhY2t4Iiwib3JnTmFtZSI6Ik9yZzEiLCJpYXQiOjE1OTI0Njg0NDh9.rgi7XDF4O03j0JiZzaUXuZJoXZtNiYaJtspRNY2shuI";
		$peer = array("peer0.org1.smartbagng.com", "peer0.org2.smartbagng.com");
		
		$headers = array(
		    'Content-Type: application/json',
		    sprintf('Authorization: Bearer %s', $authorization)
		  );
		
		$postRequest =  array(
		    'fcn' => $fcn,
		    'peers' => $peer,
			'chaincodeName' => 'fabcar',
			"channelName" => 'mychannel',
			"args"=> $args
		);
		 $post = json_encode($postRequest);
		
		$cURLConnection = curl_init();
		
		$cURLConnection = curl_init($url);
		curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,  $post);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		
		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		
		$jsonArrayResponse = $apiResponse;
		
		
		return $jsonArrayResponse;
		
	}
