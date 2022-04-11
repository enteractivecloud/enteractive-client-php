<?php 
// Model representing the request for closing players in bulk
class ClosePlayersRequest{
	// List of client player details for the players to close
	public $closePlayers;
}

// Model representing the json schema for closing players
class ClosePlayerRequest
{
	// Player's brand name
	public $brandName;
	
	// Unique identifier for this player	
	public $clientUserId;
}

?>