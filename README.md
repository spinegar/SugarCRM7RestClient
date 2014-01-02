SugarCRM 7 REST Client
===============================================

- Version: 2.0
- License: MIT


Contents
--------
1. About
2. Installation
3. Usage
4. Usage Examples
5. To Do


1.About
-------
- This wrapper simplies interacting with the SugarCRM 7 REST API.
- All resources and parameters should be configured per the API documentation 
(See http://YourSugarInstance/rest/v10/help)

2. Installation via Composer
----------------------------
Edit composer.json

	{
		"require": {
			"spinegar/sugar7wrapper": "development"
		},
		"minimum-stability": "dev"
	}

Then install with composer

	$ composer install


3.Usage 
-------
To use this API follow these steps.

1. Instantiate

		$sugar = new \Spinegar\Sugar7Wrapper\Rest();
	

2. Set the API URL and credentials for the API user.
	
		$sugar->setUrl('https://sugar/rest/v10/')
			->setUsername('restUser')
			->setPassword('password');

3. Call any existing endpoint on the REST API with its proper HTTP Method. 
Note: $endpoint and $parameters should be configured per the API documentation (See http://YourSugarInstance/rest/v10/help).

		$sugar->get($endpoint, $parameters);
		$sugar->post($endpoint, $parameters);
		$sugar->put($endpoint, $parameters);
		$sugar->delete($endpoint, $parameters); 


4.Usage Examples
---------------

	/* Instantiate and authenticate */
	$sugar = new \Spinegar\Sugar7Wrapper\Rest();

	$sugar->setUrl('https://sugar/rest/v10/')
		->setUsername('restUser')
		->setPassword('password');

	/* Retrieve records in the Cases module (default is to retrieve 20 records) */
	$sugar->get('Cases');
	
	/* Retrieve a specific number of records */
	$parameters = array(
		'max_num' => 100
	);

	$sugar->get('Cases', $parameters);

	/* Retrieve all records in the Cases module where the name = 'Case1 Name' or 'Case2 Name' */
	$parameters = array(
		'q' => '"Case1 Name" "Case2 Name"'
	);

	$sugar->get('Cases', $parameters); 

	/* Retrieve the name field for all records in the Cases module */
	$parameters = array(
		'name' => 'Case Name'
	);

	$sugar->get('Cases', $parameters); 

	/* Retrieve a specific record from the Cases module */
	$record_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';

	$sugar->get('Cases/' . $record_id);

	/* Create a case */
	$parameters = array(
		'name' => 'Case Name'
	);

	$sugar->post('Cases', $parameters); 

	/* Update a case */
	$record_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';

	$parameters = array(
		'name' => 'Update Case Name'
	);

	$sugar->put('Cases/' . $record_id, $parameters); 

	/* Favorite a case */
	$record_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';

	$sugar->put('Cases/' . $record_id . '/favorite'); 

	/* Unfavorite a case */
	$record_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';

	$sugar->delete('Cases/' . $record_id . '/favorite'); 

	/* Retrieve cases related to an account */
	$record_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';
	$link_name = 'contacts';

	$sugar->get('Cases/' . $record_id . '/link/' . $link_name'); 

	/* Relate a case to an account */
	$account_id = 'f2fbcdd4-83c0-4cad-96da-35e79534d8a1';
	$case_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';
	$link_name = 'case';

	$sugar->post('Accounts/' . $account_id . '/link/' . $link_name . '/' . $case_id'); 

	/* Delete relationship between an account and case */
	$account_id = 'f2fbcdd4-83c0-4cad-96da-35e79534d8a1';
	$case_id = '0188996f-9d3b-4d6b-a332-ac08cc716333';
	$link_name = 'case';

	$sugar->delete('Accounts/' . $account_id . '/link/' . $link_name . '/' . $case_id'); 

	/* Retrieve a list of attachments for a case */
	$record_id =  '0188996f-9d3b-4d6b-a332-ac08cc716333';
	$link_name = 'notes';

	$attachments = $sugar->get('Cases/' . $record_id . '/link/' . $link_name'); 

	foreach($attachments['records'] as $attachment)
	{
		$output[] = $sugar->get('Notes/' . $attachment['id'] . '/file');
	}

	return $output;

	/* Delete the file associated to the filename field of a note */
	$record_id = 'ef7741d7-efed-4deb-8b67-c0ffa678f51d';
	$field = 'filename';

	$sugar->delete('Notes/' . $record_id . '/files/' . $field')

	
5. To Do
=========

	- [x] Commit Base Class For Version 2
	- [ ] Figure Out How To Handle File Downloads And Uploads
	- [ ] Complete Documentation With Many Examples
	- [ ] Write Unit Tests
