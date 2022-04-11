<?php
require_once 'models/ImportPlayer.php';
require_once 'models/request/ImportPlayerRequest.php';

$apiUrl = "https://stagingapi.enteractive.se/"; 
$password = "**************";
$username = "demo@enteractive.se";

run();

// Players import process
function run(){
	
	//Retrieving player data
	$playerData = getPlayerData();
	
	//Mapping Request object
	$requestData = requestMapper($playerData);
	$request = new AddPlayerRequest();
	$request->players = $requestData; 
	$request->campaignType = "Reactivation";
	
	//Send reuqest to Enteractive's Client API 
	sendRequest($request);
}

// Sends Player Import request to Enteractive's Client API 
function sendRequest($request){
	global $apiUrl;
	
	$url = $apiUrl . 'react/2.0/Player/AddPlayers';
	$body = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($request));
	$token = getToken();	
	
	$options = array(
	'http' => array(
		'header'  => array("Authorization: Bearer " . $token, "Content-type: application/json" ),
		'method'  => 'POST',
		'content' => $body,
		'ignore_errors' => true
		)
	);
	
	echo("Request: ". $body. "</br></br>");
	
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	
	//Log Result	
	echo("Response: " . $result . "</br></br>");
	file_put_contents('logsImport.txt', date('Y-m-d H:i:s') . " - " . $result.PHP_EOL , FILE_APPEND | LOCK_EX);
	
	//Handle response from Enteractive's API and act accordingly 
	$resultObject = json_decode($result, true);	
	if($resultObject['Success'] == "true"){
		echo (count($resultObject['PlayersImported']) . " Players Imported Sucessfully </br>");
		echo (count($resultObject['PlayersRejected']) . " Players Rejected </br>");
	}else{
		echo ('Error: ' . $resultObject['ErrorMessage']. "</br>");
	}
	
}

// Retrieves authentication token for Enteractive's Client API 
function getToken(){	
	global $apiUrl, $username, $password;
	
	$url = $apiUrl . 'token';
	$data = array('grant_type' => 'password', 'username' => $username , 'password' => $password);

	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	 
	$resultObject = json_decode($result, true);
	
	echo("Token: " .$result . "</br></br>");	
	file_put_contents('logsImport.txt', date('Y-m-d H:i:s') . " - Token: " . $result.PHP_EOL , FILE_APPEND | LOCK_EX);
	return $resultObject['access_token'];
}

// Maps the Csv Data into the Request object utilized by Enteractive's Client API
function requestMapper($players){
	$playersRequest = array();
	
	foreach ($players as $player)
	{
		$playerData = new ImportPlayer();
		$playerData->brandName = $player->brand;
		$playerData->clientUserId = $player->userId;
		$playerData->userName = $player->username;
		$playerData->firstName = $player->firstName;
		$playerData->lastName = $player->lastName;
		$playerData->mobile = $player->mobile;
		$playerData->countryISO2 = $player->country;
		$playerData->lastDepositDate = $player->depositDate;
		$playerData->additionalAttributes =  array($player->attribute);
		$playerData->voiceConsent = $player->voiceConsent;
		$playerData->smsConsent = $player->smsConsent;
		
		array_push($playersRequest, $playerData);
	}
	echo("Request mapped successfully</br>");
	file_put_contents('logsImport.txt', date('Y-m-d H:i:s') . " - Request mapped successfully".PHP_EOL , FILE_APPEND | LOCK_EX);
	return $playersRequest;
}

// Imports Player Data into an array of ImportPlayer Objects
// This method should be updated to gather data from a relevant data source (database, api, csv, etc.)
function getPlayerData(){
	
	$player1 = new Player();
	$player1->brand = "Brand 1";
	$player1->userId = "b001";
	$player1->username = "JohnDoe";
	$player1->country = 'fi';
	$player1->firstName = "John";
	$player1->lastName = "Doe";
	$player1->mobile = "35679000001";
	$player1->depositDate = "2022-03-08";
	$player1->attribute = "Sports";
	$player1->voiceConsent = True;
	$player1->smsConsent = True;
	
	$player2 = new Player();
	$player2->brand = "Brand 1";
	$player2->userId = "b002";
	$player2->username = "jtaylor";
	$player2->country = 'gb';
	$player2->firstName = "Jane";
	$player2->lastName = "Taylor";
	$player2->mobile = "35679000002";
	$player2->depositDate = "2022-03-01";
	$player2->attribute = "Casino";
	$player2->voiceConsent = True;
	$player2->smsConsent = False;
	
	$player3 = new Player();
	$player3->brand = "Brand 1";
	$player3->userId = "b003";
	$player3->username = "JoeSmith";
	$player3->country = 'fi';
	$player3->firstName = "Joseph";
	$player3->lastName = "Smith";
	$player3->mobile = "35679000003";
	$player3->depositDate = "2022-03-10";
	$player3->attribute = "Poker";
	$player3->voiceConsent = True;
	$player3->smsConsent = True;
	
	$player4 = new Player();
	$player4->brand = "Brand 2";
	$player4->userId = "b004";
	$player4->username = "KurtM";
	$player4->country = 'gb';
	$player4->firstName = "Kurt";
	$player4->lastName = "Morrison";
	$player4->mobile = "35679000004";
	$player4->depositDate = "2022-03-18";
	$player4->attribute = "Lotto";
	$player4->voiceConsent = False;
	$player4->smsConsent = True;
	
	$player5 = new Player();
	$player5->brand = "Brand 2";
	$player5->userId = "b005";
	$player5->username = "gjones";
	$player5->country = 'fi';
	$player5->firstName = "George";
	$player5->lastName = "Jones";
	$player5->mobile = "35679000005";
	$player5->depositDate = "2022-03-20";
	$player5->attribute = "Bingo";
	$player5->voiceConsent = True;
	$player5->smsConsent = False;

	$playerData = array($player1, $player2, $player3, $player4, $player5);
	echo(count($playerData) . " Players loaded </br>");
	file_put_contents('logsImport.txt', date('Y-m-d H:i:s') ." - ".count($playerData) . " Players loaded".PHP_EOL , FILE_APPEND | LOCK_EX);
	return $playerData;
}

?>