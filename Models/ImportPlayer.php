<?php

/// Model used to get data for player import. 
/// This class represents one row of data. 
class Player{

	/// Player brand name
	public $brand;

	/// Unique identifier for this player
	public $userId;
	
	/// Player's username
	public $username;
	
	///Player's firstname
	public $firstName;
	
	/// Player's lastname
	public $lastName;
	
	/// Player's mobile number
	public $mobile;
	
	/// Player's Country code
	public $country;
	
	// Player's last deposit date required for reactivation and VFC
	public $depositDate;
	
	// Player's registration date required for NRC
	public $registrationDate;
	
	// Player's last login date
	public $lastLoginDate;
	
	// Players attributes
    // Allowed attributes: Sports, Casino, Poker, Bingo, Lotto, BetUP
	public $attribute;
	
	// Value indicating if player is to be contacted by voice calls
	public $voiceConsent;
	
	// Value indicating if player is to be contacted by sms
	public $smsConsent;
	
	// Player's AffiliateCode code
	public $affiliateCode;
	
   
}

?>