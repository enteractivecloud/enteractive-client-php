<?php
// Model representing a Sync Players API request
class SyncPlayersRequest{
	
	// List of players to process
	public $syncPlayers;
}

// Model for Sync Player details
class SyncPlayer{
	
	// Player's brand name
	public $brandName;
	
	// Unique identifier for this player
	public $clientUserId;
	
	// The date when the player last made a deposit
	public $lastDepositDate;
	
	// The date when the player last made a failed deposit
	public $failedDepositDate;
	
	// The date when the player last logged in to the system
	public $lastLoginDate;
	
	// True when the player allows to be contacted by calls
	public $voiceConsent;
	
	// True if the player allows to be contacted by sms
	public $smsConsent;
	
	// True if the player allows to be contacted by email
	public $emailConsent;
	
	// The date when the player data was last updated
	public $lastSyncDate;
}

?>