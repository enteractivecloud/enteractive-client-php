<?php
// This class demonstrates the process of synchronizing players data

require_once 'models/UpdatePlayer.php';
require_once 'models/Request/SyncPlayerRequest.php';
require_once 'models/Request/ClosePlayerRequest.php';
require_once 'Util.php';

$apiUrl = "https://stagingapi.enteractive.se/"; 
$password = "**************";
$username = "demo@enteractive.se";

updatePlayers();

// Player Synchronization process
function updatePlayers(){
	$playerData = getPlayerData(getPlayerCheckList());
	$syncPlayersArray = array();
	$closePlayers = array();
	
	foreach ($playerData as $player)
	{
		$syncPlayer = requestmapper($player);
		array_push($syncPlayersArray, $syncPlayer);
		
		if(!$player->eligible)
		{
			$closeplayer = new ClosePlayerRequest();
			$closeplayer->brandName = $syncPlayer->brandName;
			$closeplayer->clientUserId = $syncPlayer->clientUserId;
			array_push($closePlayers, $closeplayer);
		}		
	}

	syncPlayers($syncPlayersArray);
	closePlayers($closePlayers)	;
}

// Filter players which are in Player Check List
function getPlayerData($playerChecklist){
	
	$playerChecklistArray = json_decode(json_encode($playerChecklist), true)[0];
	$players = getPlayers();
	$result = array();
	
	//Get players which are in Player Check List
	foreach ($players as $player)
	{
		$search_items = array("BrandName"=> $player->brand, "ClientUserId"=>$player->userId);
		$res = search($playerChecklistArray, $search_items);

		//If player is present in Player Check List, include it in result
		if(count($res) > 0)
		{
			array_push($result, $player);
		}  
	}	
	
	file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s')." - ".count($result)." player found in Check List".PHP_EOL , FILE_APPEND | LOCK_EX);
	return $result;		
}

//Retrieve Player checklist from Enteractive's Client API
function getPlayerCheckList(){
	global $apiUrl;
	$playerCheckList = array();
	$pageSize = 5000;
	$offset = 0;
	$token = getToken();	
	$url = $apiUrl . 'react/2.0/PlayerBulk/GetPlayerCheckList';
	$endOfData = false;
	
	do{
		$body = json_encode(array('Offset' => $offset , 'PageSize' => $pageSize));

		$options = array(
			'http' => array(
				'header'  => array("Authorization: Bearer " . $token, "Content-type: application/json" ),
				'method'  => 'POST',
				'content' => $body,
				'ignore_errors' => true
			)
		);
		
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		 
		$resultObject = json_decode($result, true);
		
		if($resultObject['Success'] == "true"){			
			array_push($playerCheckList, $resultObject['PageData']);
			$endOfData = $resultObject['EndOfData'] == "true";		
			$offset = $resultObject['NextOffset'];
		}else {
			//log error 
			file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s') . " - Request failed URL: ".$url." Error Message: ".$resultObject['ErrorMessage'].PHP_EOL , FILE_APPEND | LOCK_EX);
			break;
		}	
	}while(!$endOfData);
	
	var_dump($playerCheckList);
	file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s')." - Player Check List returned ".count($playerCheckList[0])." players".PHP_EOL , FILE_APPEND | LOCK_EX);
	return $playerCheckList;
}

// Imports player details into an array of SyncPlayer Objects
// This method should be updated to gather data from a relevant data source (database, api, csv, etc.)
function getPlayers(){
	$player1 = new UpdatePlayer();
	$player1->brand = "Bet 1";
	$player1->userId = "b001";
	$player1->depositDate = "2022-05-20";
	$player1->failedDepositDate = "2022-04-11";
	$player1->lastLoginDate = "2022-05-21";
	$player1->voiceConsent = true;
	$player1->smsConsent = false;
	$player1->emailConsent = true;
	$player1->eligible = false;
	
	$player2 = new UpdatePlayer();
	$player2->brand = "Bet 1";
	$player2->userId = "b002";
	$player2->depositDate = "2022-05-02";
	$player2->failedDepositDate = "2022-04-10";
	$player2->lastLoginDate = "2022-05-22";
	$player2->voiceConsent = true;
	$player2->smsConsent = true;
	$player2->emailConsent = true;
	$player2->eligible = true;
	
	$player3 = new UpdatePlayer();
	$player3->brand = "Bet 1";
	$player3->userId = "b003";
	$player3->depositDate = "2022-05-12";
	$player3->failedDepositDate = "2022-04-13";
	$player3->lastLoginDate = "2022-05-13";
	$player3->voiceConsent = false;
	$player3->smsConsent = true;
	$player3->emailConsent = true;
	$player3->eligible = false;
	
	$player4 = new UpdatePlayer();
	$player4->brand = "Bet 2";
	$player4->userId = "b004";
	$player4->depositDate = "2022-05-01";
	$player4->failedDepositDate = "2022-04-02";
	$player4->lastLoginDate = "2022-05-10";
	$player4->voiceConsent = true;
	$player4->smsConsent = false;
	$player4->emailConsent = false;
	$player4->eligible = true;
	
	$player5 = new UpdatePlayer();
	$player5->brand = "Bet 2";
	$player5->userId = "b005";
	$player5->depositDate = "2022-04-10";
	$player5->failedDepositDate = "2022-04-02";
	$player5->lastLoginDate = "2022-05-01";
	$player5->voiceConsent = true;
	$player5->smsConsent = true;
	$player5->emailConsent = false;
	$player5->eligible = true;
	
	$playerData = array($player1, $player2, $player3, $player4, $player5);
	echo(count($playerData) . " Players loaded </br>");
	file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s') ." - ".count($playerData) . " Players loaded".PHP_EOL , FILE_APPEND | LOCK_EX);
	return $playerData;
}

// Synchronizes the players' data
function syncPlayers($syncPlayers){
	global $apiUrl;
	
	if(count($syncPlayers) > 0)
	{
		$request = new SyncPlayersRequest();
		$request->syncPlayers = $syncPlayers;
		
		$url = $apiUrl . 'react/2.0/PlayerBulk/SyncPlayers';
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
		file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s') . " - " . $result.PHP_EOL , FILE_APPEND | LOCK_EX);
		
		//Handle response from Enteractive's API and act accordingly 
		$resultObject = json_decode($result, true);	
		if($resultObject['Success'] == "true"){
			echo (count($resultObject['ConvertedPlayers']) . " Players Converted /br>");
		}else{
			echo ('Error: ' . $resultObject['ErrorMessage']. "</br>");
		}
	}
}

// Closes the players provided
function closePlayers($closePlayers){
	global $apiUrl;
	if(count($closePlayers) > 0)
	{	
		$closePlayersRequest = new ClosePlayersRequest();
		$closePlayersRequest->closePlayers = $closePlayers;
		
		$url = $apiUrl . 'react/2.0/PlayerBulk/ClosePlayers';
		$body = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($closePlayersRequest));
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
		file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s') . " - " . $result.PHP_EOL , FILE_APPEND | LOCK_EX);
		
		//Handle response from Enteractive's API and act accordingly 
		$resultObject = json_decode($result, true);	
		if($resultObject['Success'] == "true"){
			echo (count($resultObject['ClosedPlayers']) . " Players Closed /br>");
		}else{
			echo ('Error: ' . $resultObject['ErrorMessage']. "</br>");
		}
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
	file_put_contents('logsUpdate.txt', date('Y-m-d H:i:s') . " - Token: " . $result.PHP_EOL , FILE_APPEND | LOCK_EX);
	return $resultObject['access_token'];
}

// Maps the data retrieved from CSV file into the Request object utilized by Enteractive's SDK
function requestMapper($updatePlayer){
	$syncPlayer = new SyncPlayer();
	$syncPlayer->brandName = $updatePlayer->brand;
	$syncPlayer->clientUserId = $updatePlayer->userId;
	$syncPlayer->lastDepositDate = $updatePlayer->depositDate;
	$syncPlayer->failedDepositDate = $updatePlayer->failedDepositDate;
	$syncPlayer->lastLoginDate = $updatePlayer->lastLoginDate;
	$syncPlayer->voiceConsent = $updatePlayer->voiceConsent;
	$syncPlayer->smsConsent = $updatePlayer->smsConsent;
	$syncPlayer->emailConsent = $updatePlayer->emailConsent;
	$syncPlayer->lastSyncDate = date('Y-m-d H:i:s');
	
	return $syncPlayer;
}

?>