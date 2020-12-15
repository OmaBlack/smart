<?php

date_default_timezone_set("Africa/Lagos");

function CleanHouse($Values)
{
	# code... 
		$keys = trim($Values);
		$keys = strip_tags($Values);
		$keys = htmlspecialchars($Values);
		
		return $keys;
}

function newSlack($value){
	
    $json = json_encode(array(
        'text' => $value,
    ));
	
	// Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => 'https://hooks.slack.com/services/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',
	    CURLOPT_POST => 1,
	    CURLOPT_POSTFIELDS => $json
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	// Close request to clear up some resources
	curl_close($curl);
	return $resp;
}

function sucessfulMessage($Values)
{
	$keys = array("status"=>"successful", "message"=> $Values);
	return $keys;
}



function send_email($email, &$response = null, &$http_code = null) {
    $json = json_encode(array(
        'From' => $email['from'],
        'To' => $email['to'],
        'Subject' => $email['subject'],
        'HtmlBody' => $email['html_body'],
        'TextBody' => $email['text_body']
    ));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.postmarkapp.com/email');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: bcbc3cd4-1a93-466c-984e-31e64dec307d' 
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    $response = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $http_code === 200;
}