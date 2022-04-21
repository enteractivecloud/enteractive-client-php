Enteractive.Client.Php Demo
===========================

## Quick Start
- Download latest Release
- Clone the repo: git clone https://github.com/enteractivecloud/enteractive-client-php.git

## Introduction
Enteractive SDK Demo project is a basic demonstration of how to connect to the Enteractive Client API and preform basic operations on the Enteractive (Re)Activation cloud. The aim of this demo is to provide a basic integration for adding leads and data synchronisation between Enteractive and its operators. Note that the Enteractive Client API itself covers several more advanced functionalities which are not demonstrated in this demo. 

## Requirements

-	Enteractive API credentials.
-	IP whitelisting
-	[Contact Enteractive for more information](https://enteractive.com/contact-us)

## Guidelines
### Player Import
The PlayerImport.php class demonstrates the process of importing new players onto Enteractive’s ReActivation Cloud.

Flow:
1.	Get player data from a relevant data source (database, API, csv, etc.)
2.	Validate the data to make sure that a call project for the players’ country, brand and campaign exists on Enteractive and remove any invalid players from the array.
3.	Generate the add players request object (/Models/request/ImportPlayerRequest.php) with the validated player data.
4.	Process request using the Enteractive Client API endpoint AddPlayers (/react/2.0/Player/AddPlayers).
5.	Await the result from the AddPlayers endpoint and check if the request was successful.

### Player Update
The PlayerUpdate.php class demonstrates the process of updating the information of players which have already been imported on the Enteractive’s ReActivation Cloud.

Flow:
1.	Retrieve the Player Checklist from Enteractive Client API endpint (/react/2.0/PlayerBulk/GetPlayerCheckList)
2.	Get player data for the players present in the Checklist 
3.	Traverse the players’ data 
a.	Generate a sync player request object for each player (/Models/Request/SyncPlayerRequest.php)
b.	Check players eligibility and close any ineligible players 
4.	Process the request using the Enteractive Client API SyncPlayers endpoint (react/2.0/PlayerBulk/SyncPlayers)
5.	Await the result from the SyncPlayers method and check if the request was successful.

### Adapting the Player Import Class to your business requirements
#### Enteractive Client API Credentials 
The Enteractive’s Client API credentials are required to be able to initialise and operate the Enteractive Client API. The credentials are as global variables in PlayerImport.php and PlayerUpdate.php, please update these variables with your credentials and make sure they are not stored in a public repository. 

#### Data source integration
Currently the GetData() method in the PlayerImport.php and PlayerUpdate.php classes is using mock player data for demonstration purposes, this method should be updated to retrieve data from any relevant data source (database, api, csv, etc.).  

## Contributing
### Feature Requests
Have a feature request? Address your idea [here](https://enteractive.com/contact-us).
### Reporting Bugs and Issues
If you think you've found a bug, report the issue [here](https://enteractive.com/contact-us) please be sure to include as much information as possible so we can reproduce the bug.



