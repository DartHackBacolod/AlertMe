<?php
	$message = $_GET['message'];
	$phone = $_GET['phone'];
	//Configure parameters for headers and request body
	$spId = '001266'; //SP ID
	$spPassword = 's7ajBWNTNuzBG4imi743+ZbjCUo='; //SP's encrypted password
	$spServiceId = '0012662000001465'; //SP's service id
	$nonce = '2010082108334600001';// Nonce provided. Tailored for your password, use 2010082108334600001
	$creationTime = '2010-08-21T08:33:46Z';// Creation Time provided. Tailored for your password, use 2010-08-21T08:33:46Z
	$transId = '200903241230451000000110011000';// TransId provided. e.g (200903241230451000000110011000)
	$accessCode = '406861'; //SP's dedicated access code
	//$mobileNumber = '639065475318'; // Mobile number who will receive the message
	$mobileNumber = $phone;
	
	//define the web service domain
	$domainURL = 'https://npwifi.smart.com.ph/1/smsmessaging/outbound/' .$accessCode. '/requests';
	
	//json request
	$postJSONRequest = '{"outboundSMSMessageRequest":{"address":["tel:' .$mobileNumber. '"],"senderAddress":"' .$accessCode. '","outboundSMSTextMessage":{"message": "'.$message.'"}}}';
	
	//define all the request headers to be posted
	$requestHeaders = array('Content-Type: application/json', 
							'Accept: application/xml', 
							'Authorization: WSSE realm="SDP",profile="UsernameToken"', 
							'X-WSSE: UsernameToken Username="' .$spId. '",PasswordDigest="' .$spPassword. '",Nonce="' .$nonce. '", Created="' .$creationTime. '"', 
							'X-RequestHeader: request TransId="' .$transId. '",ServiceId="' .$spServiceId.'"');
							
	//initialize curl (create curl resource)
	$restSendSMS = curl_init(); 
	
	//set the domain and other options
	curl_setopt($restSendSMS, CURLOPT_URL, $domainURL);   
	curl_setopt($restSendSMS, CURLOPT_CONNECTTIMEOUT, 10); 
	curl_setopt($restSendSMS, CURLOPT_TIMEOUT, 10); 
	curl_setopt($restSendSMS, CURLOPT_RETURNTRANSFER, true);

	//load the smart server certificate
	curl_setopt($restSendSMS, CURLOPT_SSL_VERIFYPEER, true);
	$smartServerCert = '\npwifi.smart.com.ph.crt'; //certificate file (see certificate name: \npwifi.smart.com.ph.crt) must be on the same directory of this class
	curl_setopt($restSendSMS, CURLOPT_CAINFO, getcwd(). $smartServerCert);
	//curl_setopt($restSendSMS, CURLOPT_CAINFO, $smartServerCert);
	//define the post fields and the post headers 
	curl_setopt($restSendSMS, CURLOPT_POST, true); 
	curl_setopt($restSendSMS, CURLOPT_POSTFIELDS, $postJSONRequest); 
	curl_setopt($restSendSMS, CURLOPT_HTTPHEADER, $requestHeaders);
	
	//execute curl and print output
	echo "Response:<br />";
	echo $curlRespose = curl_exec($restSendSMS);
	echo "<br />";
	
	//print errors (if there are any)
	echo $curlError = curl_error($restSendSMS);  
	
	header("Location: ../index.html");
	exit;

?>