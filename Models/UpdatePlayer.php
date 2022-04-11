<?php
class UpdatePlayer{
	
	// Player's brand name
	public $brand;
	
	// Unique identifier for this player
	public $userId;
	
	// The date when the player last made a deposit
	public $depositDate;
	
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
	
	// True if the player satisfies the appropriate conditions
	public $eligible;
}
?>