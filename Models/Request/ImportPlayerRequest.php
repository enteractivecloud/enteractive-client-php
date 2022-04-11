<?php
// Model representing the schema for adding players
class AddPlayerRequest
{
	// List of Players to be imported
	public $players;
	
	// CampaignType valid entries: Reactivation, NRC, VFC, OneTimeDepositors
	public $campaignType;
}

/// Player Details Model 
class ImportPlayer{

	// Player brand name
	public $brandName;

	// Unique identifier for this player
	public $clientUserId;
	
	// Player's username
	public $userName;
	
	//Player's firstname
	public $firstName;
	
	// Player's lastname
	public $lastName;
	
	public $telephone;
	
	// Player's mobile number
	public $mobile;
	
	// Player's Country code
	public $country;
	
	//Player's email
	public $email;
	
	// Player's City
	public $city;
	
	// Player's State
	public $state;
	
	// Player's Country code
	public $countryISO2;
	
	// Player's gender (M/F)
	public $gender;
	
	// Value indicating if player is to be contacted by voice calls
	public $voiceConsent;
	
	// Value indicating if player is to be contacted by sms
	public $smsConsent;
	
	// Value indicating if player is to be contacted by email
	public $emailConsent;
	
	// Player's affiliate code
	public $affiliateCode;
	
	// Allowed statuses: Active, Frozen, Documentation pending
	public $playerStatus;
	
	// Player's lifetime deposit amount
	public $lifetimeDepositAmount;
	
	// Player's lifetime accounting revenue
	public $lifetimeAccountingRevenue;
	
	// Player's sports book bet amount
	public $sportsbookBetAmount;
	
	// Player's casino bet amount
	public $cosinoBetAmount;
	
	// Player's poker bet amount
	public $pokerBetAmount;
	
	// Player registration date * - NRC only
	public $registrationDate;
	
	// Player's last login date
	public $lastLoginDate;
	
	// Player's last deposit date * for reactivation and VFC
	public $lastDepositDate;
	
	// Player's failed deposit date
	public $failedDepositDate;
	
	// Last active date
	public $lastActiveDate;
	
	// The player's segment code
	public $segmentCode;
	
	// List of player bonus/bonuses
	public $bonusInformation;
	
	// Additional notes on player
	public $additionalNotes;
	
	// Allowed attributes: Sports,Casino,Poker,Bingo,Lotto,BetUP
	public $additionalAttributes;
	
	// Campaign expiry date
	public $campaignExpiryDate;
	
	// Player last synchronisation date
	public $lastSyncDate;

}

?>