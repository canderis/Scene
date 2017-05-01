<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/vendor/autoload.php");

/**
* spl_autoload_register is automatically called by PHP when an object of a class is created but the
* class file has not been included.
* The funtion automatically includes the relevant file.
* @param String class_name
* @author Scott Huffman
**/
spl_autoload_register( function ($class_name) {
	if (strpos($class_name, 'api') !== false) {
		require_once ( 'api_classes/' . $class_name . '.php');
	}
});

/**
*
* Scene.php
*
* Scene PHP is the master class file for the Scene app.
* An object of the Scene Class is created when a call is made to route.php via the url /SceneRequest/funtion_name
* The router will then try to call the appropriate file in this class.
* This class is responsible for all communication with the Scene user interface.
* @author Scott Huffman, Sydney Ehlinger, Eva Kuntz, & Kevin Mathes
*
*
**/
class Scene {

	private $salt = 'saltySpitoon';
	private $idMinLength = 8;

	/**
	* Prepairs the database helper for connecting to the database.
	* @author Scott Huffman
	**/
	function __construct(){
		$this->dbc = new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");
	}

	function testIdHash(){
		$hash = $this->makeHashId(5);
		echo $hash;
		echo "<br>";
		echo $this->decodeHashId($hash);
	}

	private function makeHashId($id){
		$hashids = new Hashids\Hashids($this->salt, $this->idMinLength);
		return $hashids->encode($id);
	}

	private function decodeHashId($hash){
		$hashids = new Hashids\Hashids($this->salt, $this->idMinLength);
		return $hashids->decode($hash)[0];
	}


	function controllerTest(){
		//$lat = 42.026802; //for Ames, IA
		//$lng = -93.620181;
		$mapquest = new mapquest_api(42.026802, -93.620181);
		$temp = $mapquest->get_prepped_data_array();
		//$mapquest->merge_prepped_array();
		echo "<pre>";
		print_r($temp);
		echo "</pre>";
	}

	function yelp_test_curl(){
		$yelpapi = new yelp_api(42.026802, -93.620181);
	}


	 function controllerTestYelp(){
	 	$yelpapi = new yelp_api(42.026802, -93.620181);
		
		echo "<pre>";
	 	print_r( $yelpapi->get_prepped_data_array());
		echo "</pre>";
	 }

	 function controllerTestFandango(){
		 $fandangoapi = new fandango_api(42.026802, -93.620181);
	 }

	 function controllerTestMovieDatabase(){
		 $movieDatabaseApi = new movie_database_api();
		 echo "<pre>";
	 	print_r( $movieDatabaseApi->get_prepped_data_array());
		echo "</pre>";
	 }

	 function controllerTestGoogle()
	 {
	 	$googleapi = new google_api(42.026802, -93.620181);
	 	echo "<pre>";
	 	print_r($googleapi->get_prepped_data_array());
		echo "</pre>";
	 }

	 function mergeTest()
	 {
	 	$latitude = 42.0227876; 
	 	$longitude = -93.6479798;
	 	$mapquest = new mapquest_api($latitude, $longitude);
	 	//$mapquest = $mapquest->get_prepped_data_array();
	 	$yelp = new yelp_api($latitude, $longitude);
	 	//$yelp = $yelp->get_prepped_data_array();
	 	$temp = array($mapquest,$yelp);
	 	$result = api_base_class::mergeApiArrays($temp);
	 	echo "<pre>";
		print_r($result);
		echo "</pre>";
	 }


	/**
	* Function that gets results of nearby searches from various APIs
	* and returns the results in an array
	* @author All Team Members
	* @param var latitude
	* @param var longitude
	* @return array array of results from a mapquest nearby search
	**/
	private function getResultsByLocation($latitude, $longitude, $userId){
		//var_dump( $latitude);// .' '. $longitude ;
		//$latitude = round(floatval($latitude), 2);
		//$longitude = round(floatval($longitude), 2);
		
		//$latitude = 42.03; $longitude = -93.65;
		//var_dump($latitude, $longitude);
		$mapquest = new mapquest_api($latitude, $longitude);
	 	//$mapquest = $mapquest->get_prepped_data_array();
	 	$yelp = new yelp_api($latitude, $longitude);
	 	//$yelp = $yelp->get_prepped_data_array();
	 	//$temp = array($mapquest);
	 	$merged = api_base_class::mergeApiArrays(array($mapquest, $yelp));
	 	$interested = $this->create_scene_queue($merged, $userId);
	 	return array('suggested'=>$merged, 'interested'=>$interested);
	}

	/**
	* Function that echos json data back to client for reading by sencha
	* @param boolean result is the result boolean, true if the code executed successfully, false if it fails.
	* @param array data is whatever info needed to be passed back to the client
	* @author Scott Huffman
	**/
	private function echoJson($result ,$data = null){
		if($result)
			echo json_encode(array('success'=>$result, 'data'=>$data));
		else{
			echo json_encode(array('success'=>$result, 'error'=>$data));
		}
	}

	/**
	* Function that handles the Facebook login. The JavaScript side connects to facebook and passes a
	* signed_request token.
	* The Facebook canvas helper interprets the token, verrifies it and opens a dialog wit the facebook API,
	* The funtion gets the user's info from Facebook, and uses that to either create an accout or connect to.
	* an existing account. It then passes the data back to the client about the user, and gathers the results
	* to populate the app.
	*
	* @param string POST signed_request Facebook request token
	* @param string POST latitude
	* @param string POST longitude
	*
	* @author Scott Huffman
	* @return void
	**/
	public function handleFacebookLogin(){
		$fb = new Facebook\Facebook([
			'app_id' => '1832851696981861',
			'app_secret' => '7300d7d3dfeae48324e58f80163de44e',
			'default_graph_version' => 'v2.8',
		]);


		$helper = $fb->getCanvasHelper();

		try {
			$accessToken = $helper->getAccessToken();
		}
		catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$this->echoJson(false, 'Graph returned an error: ' . $e->getMessage());
			return;
		}
		catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$this->echoJson(false, 'Facebook SDK returned an error: ' . $e->getMessage() );
			return;
		}

		if (! isset($accessToken)) {
		  $this->echoJson(false, 'No cookie set or no OAuth data could be obtained from cookie.');
		  return;
		}

		$fb->setDefaultAccessToken( $accessToken);

		$permissions = $fb->get('/me/permissions');
		$permissions = $permissions->getGraphEdge()->asArray();
		
		foreach ($permissions as $permission) {
			if( $permission['status'] == 'declined'){
				return $this->echoJson(false, 'Please provide your email address.' );
			}
		}

		try {
			//
			$response = $fb->get('/me?fields=id,name,email');
			$user = $response->getGraphObject();
			//print_r($user);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$this->echoJson(false, 'Facebook SDK returned an error: ' . $e->getMessage() );
			return;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$this->echoJson(false, 'Facebook SDK returned an error: ' . $e->getMessage() );
			return;
		}

		

		$existingUserData = $this->validate_user($user['id']);
		if(count($existingUserData) === 0){
			$existingUserData = $this->insert_into_user_info($user['name'], $user['id'], $user['email'] );
		}

		$locationResults = $this->getResultsByLocation($_POST['latitude'], $_POST['longitude'], $existingUserData[0]['id']);

		$this->echoJson(
			true,
			array(
				 'userInfo'		=> $existingUserData[0]
				,'suggestedList'=> array_values($locationResults['suggested'])
				,'interestedList'=> array_values($locationResults['interested'])

			)
		);
		return;
	}

	/**
	* Function to insert new user data into our user_info database
	* @param string name - varchar(40). Account's name
	* @param string fb_user_id - varchar(16). User's Facebook id if they log in with their Facebook account
	* @param string email_address - varchar(255). Account's email address
	* @param string password - varchar(255). Account's password
	* @param var account_type - int(1). Could be a 0, 1, 2. 0 = primary user account, 1 = company account, 2 = admin account
	* @return $that the user was placed in the database
	* @author Sydney Ehlinger
	*/
	public function insert_into_user_info($name, $fb_user_id = null, $email_address = null, $password = null, $account_type = 1){
		$insert = "INSERT INTO user_info (name, fb_user_id, email_address, password, account_type)
		VALUES (?,?,?,?,?)";

		$this->dbc->query($insert, array($name, $fb_user_id, $email_address,$password,$account_type));
		return $this->validate_user($fb_user_id);
	}

	/**
	* Function that validates that a user exists in our user_info database table based on the user's id
	* @param var id - int. Account's Facebook id
	* @return array user's info or empty array
	* @author Sydney Ehlinger
	*/
	private function validate_user($id){
		$validate = "SELECT `id`, `name`, `fb_user_id`, `email_address`, `account_type` FROM user_info where fb_user_id = ? ";
		$this->dbc->query($validate, array($id));
		$result = $this->dbc->fetch_all_assoc();
		return $result;
	}

	
	/**
	* Helper function that validates a user's email and password
	* to login to Scene without a FB account.
	* 
	* Password param is md5'd
	* @author Scott Huffman & Eva Kuntz
	**/
	private function validate_userByEmailAndPass($email, $pass){
		$validate = "SELECT * FROM user_info where email_address = ? AND password=?";
		$this->dbc->query($validate, array($email, $pass));
		$result = $this->dbc->fetch_all_assoc();

		if(count($result) === 0){
			return array('success'=>false, 'error'=> 'account does not exist');
		}
		$result = $result[0];

		unset($result['password']);
		return array('success'=>true, 'data'=> $result );
	}


	/**
	* Function that updates the users "interest"
	* (either to interest or disinterest)
	* for any given scene.
	* NOTE: 0-scene has not been 'touched'
	*	1-user is interested in scene
	*	2-user is disinterested in scene
	* @author Eva Kuntz, and Scott Huffman
	* @param string user_id
	* @param string scene_id that user is attempting to update
	* @param string user_interest (1 if interested, 2 if not interested)
	* @return array MySQL result (whether or not update was successful)
	**/
	private function update_user_interest($user_id, $scene_id, $user_interest){
		if(count($this->select_user_opinion($user_id, $scene_id)) > 0){
			//user_interest is an integer
			$sql_update =
				"UPDATE user_opinion
				SET scene_user_interest = ?
				WHERE user_info_id = ? AND scene_id = ?";

			$this->dbc->query($sql_update, array($user_interest, $user_id, $scene_id ));
			return $this->select_user_opinion($user_id, $scene_id);
		}

		$insert = "INSERT into user_opinion ( scene_user_interest, user_info_id, scene_id ) values (?, ?, ?)";
		$this->dbc->query($insert, array($user_interest, $user_id, $scene_id ));

		$userOpinion = $this->select_user_opinion($user_id, $scene_id);
		if(count($userOpinion) < 1)
			return false;

		return $userOpinion;
	}

	private function select_user_opinion($user_id, $scene_id){
		$select = "SELECT * FROM user_opinion where user_info_id = ? AND scene_id = ?";
		//print_r(array($user_id, $scene_id));
		$this->dbc->query($select, array($user_id, $scene_id));

		//print_r();
		return $this->dbc->fetch_all_assoc();
	}

	/**
	* Function that updates a given scene event
	* with the user's new user_opinion for interested events.
	**/
	public function user_interested(){
		$sceneId = preg_replace("/(\W)+/", '', $_POST['key'] . $_POST['companyName']);

		if( false === $result = $this->update_user_interest($_POST['account_id'], $sceneId, 1 ) )
			return $this->echoJson( false, 'something failed' );
		return $this->echoJson( true, $result );
	}

	/**
	* Function that updates a given scene event
	* with the user's new user_opinion for dis-interested events.
	**/
	public function user_disinterested(){
		$sceneId = preg_replace("/(\W)+/", '', $_POST['key'] . $_POST['companyName']);

		if( false === $result = $this->update_user_interest($_POST['account_id'], $sceneId, 2 ) )
			return $this->echoJson( false, 'something failed' );
		return $this->echoJson( true, $result );

	}


	/**
	* Function that creates and returns an event's
	* scene_id
	* @author Eva Kuntz
	* @param string $curr_key the event's current key value (stripped address)
	* @param string $company_name the company's name
	* @return string that is the 'new' scene_id, which is a combination
	* of the current and company key name.
	**/
	public function create_scene_id($curr_key, $company_name){
		return preg_replace("/(\W)+/", '', $curr_key.$company_name);
	}

	/**
	*Function that updates the user's "like"
	*(either to like or dislike)
	*for any given scene.
	*NOTE: 0-scene has not been 'touched'
	*	1-user liked the scene scene
	*	2-user disliked the scene
	* @author Eva Kuntz
	* @param string user_id
	* @param string scene_id that user is attempting to update
	* @param int user_like (1 if like, 2 if dislike)
	* @return array MySQL result (whether or not update was successful)
	**/
	private function update_user_like($user_id, $scene_id, $user_like){
		if(count($this->select_user_opinion($user_id, $scene_id)) > 0){
			//user_interest is an integer
			$sql_update =
				"UPDATE user_opinion
				SET scene_user_like = ?
				WHERE user_info_id = ? AND scene_id = ?";

			$this->dbc->query($sql_update, array($user_like, $user_id, $scene_id ));
			return $this->select_user_opinion($user_id, $scene_id);
		}

		//otherwise, insert the entry into db
		$insert = "INSERT into user_opinion ( scene_user_like, user_info_id, scene_id ) values (?, ?, ?)";
		$this->dbc->query($insert, array($user_like, $user_id, $scene_id ));

		$userOpinion = $this->select_user_opinion($user_id, $scene_id);
		if(count($userOpinion) < 1)
			return false;

		return $userOpinion;
	}

	public function user_like(){
		$sceneId = preg_replace("/(\W)+/", '', $_POST['key'] . $_POST['companyName']);

		if( false === $result = $this->update_user_like($_POST['account_id'], $sceneId, 1 ) )
			return $this->echoJson( false, 'something failed' );
		return $this->echoJson( true, $result );
	}


	public function user_dislike(){
		$sceneId = preg_replace("/(\W)+/", '', $_POST['key'] . $_POST['companyName']);

		if( false === $result = $this->update_user_like($_POST['account_id'], $sceneId, 2 ) )
			return $this->echoJson( false, 'something failed' );
		return $this->echoJson( true, $result );

	}

	/**
	*Function that returns an array of tuples
	*of user liked scenes based on user_id from database.
	* @author Eva Kuntz
	* @param string user_id
	* @return array MySQL result of the query (array of tuples)
	**/
	private function get_user_likes($user_id){
		$sql_query = "SELECT * FROM user_opinion u WHERE u.user_info_id='$user_id'
		and u.scene_user_like=1";

		$this->dbc->query($sql_query);
		$result = $this->dbc->fetch_all_assoc();
		return $result;
	}


	/**
	* Function that returns an array of tuples
	* of user interested scenes based on user_id from database
	*Function that returns an array of tuples
	*of user interested scenes based on user_id from database
	* @author Eva Kuntz
	* @param string user_id
	* @return array MySQL query result (array of tuples)
	**/
	private function get_user_interested($user_id){
		$sql_query = "SELECT * FROM user_opinion u WHERE u.user_info_id=? and u.scene_user_interest = 1 and scene_user_like != 2
		and u.scene_user_interest=1";

		$this->dbc->query($sql_query, array($user_id));
		$result = $this->dbc->fetch_all_assoc();
		return $result;
	}


	/**
	* Function that takes a merged array of all
	* the api search results and compares the scenes
	* to the liked/disliked, interested/not interested
	* scenes in the db based on the the current user's id.
	*
	* Once scenes are formatted appropriately (separated into
	* interested scenes for the user view "interested scenes"),
	* the result will be a json object that contains a full
	* scene queue array and a list of interested scenes.
	*
	* @param array scenes (merged events array)
	* @param string user_id
	* @return array of interested events and the event details
	**/
	public function create_scene_queue($merged, $user_id){//$scenes, $user_id){
		//get list of events user is interested in from db
		$interested_events_db = $this->get_user_interested($user_id);
		$interested_events = [];

		//$merged = $this->mergeTest(); //get merged search results
		foreach($merged as $key => $event){
			//get the event's current key and the event company name
			// these will be used to create the 'scene_id' for comparison
			$curr_key = "";
			$company_name = "";
			if(isset($event["uniqueId"])){
				$curr_key = $event["uniqueId"];
			}else{
				$curr_key = $key;
			}

			//$curr_key = $key;

			if(isset($event["name"])){
				$company_name = $event["name"];
			}else{
				$company_name = $event["name_y"];
			}

			//create the scene id for comparison
			$scene_event_id = $this->create_scene_id($curr_key, $company_name);

			//compare the merged events to the interested events list
			// from the db
			foreach($interested_events_db as $key => $user_event){
				//if the id's match, add the event to the list of
				// interested events to display to the user
				if($scene_event_id === $user_event["scene_id"]){
					//add event to the list of interested events to
					// display to users
					$interested_events[$scene_event_id] = $event;
				}
			}
		}
		//return the array of intersted events; includes event details
		return $interested_events;
	}


	/**
	* SUGGESTION ENGINE OF APP
	**/
	private function create_suggested_scene_list(){
		return NULL;
	}


	public function handleNonFacebookLogin(){
		$userData = $this->validate_userByEmailAndPass($_POST['email'], md5($_POST['pass']));
		//print_r($userData);

		if($userData['success']){
			$locationResults = $this->getResultsByLocation($_POST['latitude'], $_POST['longitude'], $userData['data']['id']);

			return $this->echoJson(
				true,
				array(
					 'userInfo'		=> $userData['data']
					,'suggestedList'=> array_values($locationResults['suggested'])
					,'interestedList'=> array_values($locationResults['interested'])

				)
			);
		}

		return $this->echoJson(
			false,
			$userData['error']
		);
	}

	public function handleCompanySearch(){
		$sceneId = preg_replace("/(\W)+/", '', $_POST['locationAddress'] . $_POST['locationName']);

		$select = "SELECT * FROM User_Opinion where scene_id = ?";
		$this->dbc->query($select, array($sceneId));

		$this->echoJson(
			true,
			$this->dbc->fetch_all_assoc()
		);
	}

	/**
	* Function that services login without facebook
	* @author Eva Kuntz
	**/
	public function user_login_no_fb(){
		//get username (email) and password from POST request
		$user = $_POST["username"];
		$password = md5($_POST["password"]);

		$select = "";

		//if admin, make sure account matches
		if(isset($_POST["account_type"])){
			$account_type = $_POST["account_type"];
			$select = "SELECT * FROM  user_info WHERE email_address=? AND password=? AND account_type='3'";
		}else{
			$select = "SELECT * FROM  user_info WHERE email_address=? AND password=?";
		}

		//$this->echoJson(true,$account_type);

		$this->dbc->query($select, array($user,$password));

		if(count($this->dbc->fetch_all_assoc()) > 0){
			$this->echoJson(true,null);
		}else{
			$this->echoJson(false,null);
		}
	}


	/**
	* Function that creates a user's
	* Scene profile (what types of things
	* a user does and does not like).
	* Once information is validated,
	* user's profile information is inserted into the database.
	* @author Eva Kuntz
	**/
	public function createUserProfile($data, $user_id){
		//decode the json string sent from client
		//$data = json_decode($_POST["data"]);

		//get user's scene id
		//$user_id = $_POST["user_id"];

		/**{
			FORMAT FOR $DATA OBJECTs
			0 => array(
				profile_category_id => 0,
				profile_category_choice => ...,
								)
			1 => array(
				profile_category_id => 0,
				profile_category_choice => ...,
								)

		}**/

		//print_r($user_id);

		//prepare insert query
		$insert = "INSERT INTO user_profile (user_info_id, profile_category_choice) VALUES (?,?)";

		//iterate through json object and insert into database
		foreach ($data as $value) {

			//$key is an integer index in the array
			$this->dbc->query($insert, array($user_id, $value["profile_options_id"]));
		}

		//$this->echoJson("true", NULL);
		return true;

	}


	/**
	* Function that creates a user account
	* (w/o FB) and inserts the new user
	* into the database.
	* @author Eva Kuntz
	**/
	public function createUserAccount(){

			//vars for all form fields
			$name = $age = $email = $pass = $passConfirm = "";
			$ERROR_COUNT = 0;

			//format data
			if(isset($_POST["name"])){ $name = htmlspecialchars($_POST["name"]); }
			if(isset($_POST["age"])){ $age = htmlspecialchars($_POST["age"]); }
			if(isset($_POST["email"])){ $email = htmlspecialchars($_POST["email"]); }
			if(isset($_POST["password"])){ $pass = htmlspecialchars($_POST["password"]);}
			if(isset($_POST["passwordConfirm"])){ $passConfirm = htmlspecialchars($_POST["passwordConfirm"]); }

			//call all field's validation functions
			$nameError= $this->validateName($name);
			$ERROR_COUNT+=$nameError["error_count"];
			$nameError = $nameError["error_msg"];

			$passError = $this->validatePassword($pass, $passConfirm);
			$ERROR_COUNT+=$passError["error_count"];
			$passError = $passError["error_msg"];

			$emailError = $this->validateEmail($email);
			$ERROR_COUNT+=$emailError["error_count"];
			$emailError = $emailError["error_msg"];


			if($ERROR_COUNT != 0){
			//if there are errors, return the errors in json string
			//echo json_encode( array("success"=>false,"nameError"=>$nameError, "emailError"=>$emailError, "passError"=>$passError));
				
				$this->echoJson(false, $nameError . '<br>' . $emailError . '<br>'. $passError);
			}else{

				//echo $this->echoJson(true,array($pass, $passConfirm));
				//return;
				$insert = "INSERT INTO user_info ( name, fb_user_id, email_address, password, account_type) VALUES (?, ?,?, ?, ?)";

				//insert user info into db
				$this->dbc->query($insert, array($name, NULL, $email, md5($pass), 1));

				$this->dbc->query("SELECT * FROM user_info WHERE email_address =?", array($email));
				$result = $this->dbc->fetch_all_assoc();
				if(count($result) > 0){
					$id = $result[0]["id"]; //get user id
					//echo back user id (number) and age range
					$this->echoJson(true, array("id"=>$id, "age"=>$age));
				}else{
					echo $this->echoJson(false,null);
				}
			}

	}


	function request_admin_account(){
		//vars for all form fields
			$name = $email = $pass = $passConfirm = "";
			$ERROR_COUNT = 0;

			//format data
			if(isset($_POST["name"])){ $name = htmlspecialchars($_POST["name"]); }
			if(isset($_POST["email"])){ $email = htmlspecialchars($_POST["email"]); }
			if(isset($_POST["password"])){ $pass = htmlspecialchars($_POST["password"]);}
			if(isset($_POST["passwordConfirm"])){ $passConfirm = htmlspecialchars($_POST["passwordConfirm"]); }

			//call all field's validation functions
			$nameError= $this->validateName($name);
			$ERROR_COUNT+=$nameError["error_count"];
			$nameError = $nameError["error_msg"];

			$passError = $this->validatePassword($pass, $passConfirm);
			$ERROR_COUNT+=$passError["error_count"];
			$passError = $passError["error_msg"];

			$emailError = $this->validateEmail($email);
			$ERROR_COUNT+=$emailError["error_count"];
			$emailError = $emailError["error_msg"];

			if($ERROR_COUNT != 0){
			//if there are errors, return the errors in json string
			//echo json_encode( array("success"=>false,"nameError"=>$nameError, "emailError"=>$emailError, "passError"=>$passError));
				$this->echoJson(false, array("nameError"=>$nameError, "emailError"=>$emailError, "passError"=>$passError));
			}else{

				$insert = "INSERT INTO admin_info (user_info_id, name,email_address,password,comment) VALUES (?,?,?,?,?)";
				$this->dbc->query($insert,array(null,$name, $email,md5($pass), NULL));

				$this->dbc->query("SELECT * FROM admin_info WHERE email_address =?", array($email));
				$result = $this->dbc->fetch_all_assoc();
				if(count($result) > 0){
					$id = $result[0]["id"]; //get user id
					
					//echo back user id (number)
					$this->echoJson(true, $id);
				}else{
					$this->echoJson(false,null);
				}
			}
	}

	/**
	* Function that strips and validates an email address
	* @author Eva Kuntz
	* @param string $email email to be validated
	* @return error if email is not validated, otherwise, returns empty string
	**/
	function validateEmail($email){
		if($this->check_if_user_exists($email) == 1){
			//$GLOBALS['ERROR_COUNT']++;
			return array("error_count"=>1, "error_msg"=>"This email already exists.");
		}
		else if(!$email || strlen(trim($email)) < 1){
			return array("error_count"=>1, "error_msg"=>"Email is required.");
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			//check if valide email using filter_var function
			return array("error_count"=>1, "error_msg"=>"Email is not valid.");
		}
		return array("error_count"=>0, "error_msg"=>"");
	}

	/**
	* Sends a new user's proposed email address
	* to the database to see if their username
	* already exists.
	* @author Eva Kuntz
	**/
	function check_if_user_exists($email){
		//$email = $this->format_username($email);

		$select = "SELECT * FROM user_info WHERE email_address=?";
		$this->dbc->query($select, array($email));

		if(count($this->dbc->fetch_all_assoc()) > 0){
			return 1;
		}
		return 0;
	}


	/**
	* Function that validates user's proposed password.
	* Password and Password Confirm must match.
	* @author Eva Kuntz
	* @param string $pass
	* @param string $passConfirm
	* @return error if not valid, otherwise returns empty string.
	**/
	function validatePassword($pass, $passConfirm){
		if(!$pass || !$passConfirm || strlen(trim($pass)) < 1 || strlen(trim($passConfirm)) < 1){
			return array("error_count"=>1, "error_msg"=>"Password is required.");
		}
		else if($pass !== $passConfirm){
			return array("error_count"=>1, "error_msg"=>"Passwords must match.");
		}
		return array("error_count"=>0, "error_msg"=>"");
	}

	/**
	* Function that validates a user's entered name.
	* Name must contain only alphabetic characters.
	* @author Eva Kuntz
	* @param string $name
	* @return error if name is not valid, otherwise returns empty string.
	**/
	function validateName($name){
		$name = preg_replace('/\s+/', '', $name); //get rid of all whitespace
		if(!$name || strlen($name) < 1){
			return array("error_count"=>1, "error_msg"=>"Name is required.");
		}
		else if(!ctype_alpha($name)){
			return array("error_count"=>1, "error_msg"=>"Name may only contain alphabetic characters.");

		}
		return array("error_count"=>0, "error_msg"=>"");
	}

	/**
	* Function that takes the user's email address
	* and formats is correctly to be the user's username.
	* @author Eva Kuntz
	**/
	function format_username($email){
		$result = "";
		$i = 0; //index

		while($email[$i] !== '@'){
		$result.=$email[$i];
		$i++;
		}
		return $result;
	}


	/**
	* Function that adds a new profile category
	* (list of binary choice) to the database.
	* @author Eva Kuntz
	**/
	function add_new_profile_category(){
		$choice_type = $_POST["choiceType"];
		$name = $_POST["name"];
		$options = $_POST["options"];
		$type = 0;

		//if choice is binary, change the type of the option
		if($choice_type === "binary"){
			$type = 1;
		}

		//$this->echoJson(true,array($choice_type,$name,$options,$type));

		$insert = "INSERT INTO profile_category (name,choiceType) VALUES (?,?)";
		$this->dbc->query($insert, array($name,$type));
		$this->dbc->query("SELECT id FROM profile_category WHERE name = ?", array($name));
		//$temp = $this->dbc->fetch_all_assoc();
		$category_id = $this->dbc->fetch_all_assoc()[0]["id"];

		
		//FOR EACH OPTION, DO ANOTHER INSERT INTO DB PROFILE_OPTIONS(?) FOR EACH OPTION
		foreach($options as $key=>$value){
			$insert = "INSERT INTO profile_options (profile_category_id,option_text) VALUES (?,?)";
			$this->dbc->query($insert, array($category_id, $value)); 
			//$this->echoJson(true,$value);
		}

		$this->echoJson(true, null);

		}

	/**
	* Function that prepares a query
	* to select and format all the
	* profile categories and their options.
	* Response is sent back to client
	* for updating and displaying purposes.
	* @author Eva Kuntz & Scott Huffman
	**/
	function get_current_profile_categories(){
		$select =
			"SELECT
				profile_category.id
				, profile_category.name
				, profile_category.choiceType
				, profile_options.id as profile_options_id
				, profile_options.option_text
			FROM `profile_category`
			LEFT JOIN profile_options on profile_category.id = profile_options.profile_category_id";

		$this->dbc->query($select);

		$result = $this->dbc->fetch_all_assoc();

		$output = array();
		foreach ($result as $record) {
			if(!isset($output[ $record['name'] ] )){
				$output[$record['name']] = array(
					 'id' => $record['id']
					,'options'=> array(
						array(
							 'option'=>$record['option_text']
							,'profile_options_id' => $record['profile_options_id']
						)
					),
					'choiceType'=>$record['choiceType']
				);
				continue;
			}
			$output[$record['name']]['options'][] =
				array('option'=>$record['option_text'],'profile_options_id'=> $record['profile_options_id']);
		}

		$final = array();
		foreach ($output as $key => $value) {
			$array = $value;
			$array['name'] = $key;
			$final[] = $array;
		}

		$this->echoJson(true, $final);
		return;
	}

	function submitProfile(){
		//print_r();
		$this->createUserProfile(json_decode($_POST['data'],true), $_POST['userId']);

		$validate = "SELECT `id`, `name`, `fb_user_id`, `email_address`, `account_type` FROM user_info where id = ?";
		$this->dbc->query($validate, array($_POST['userId']));
		$userData = $this->dbc->fetch_all_assoc();
		$userData = $userData[0];

		//print_r($userData);

		$locationResults = $this->getResultsByLocation($_POST['latitude'], $_POST['longitude'], $userData['id']);

		$this->echoJson(
			true,
			array(
				 'userInfo'		=> $userData
				,'suggestedList'=> array_values($locationResults['suggested'])
				,'interestedList'=> array_values($locationResults['interested'])

			)
		);
	}

	function processSuggestions($aroundMe){

		//need more good data to test
		//need to weigh by how many other people liked stuff
		$getUserProfile = "
			SELECT DISTINCT option_text, name, user_opinion.scene_id
			FROM user_profile 
			LEFT JOIN profile_options ON user_profile.profile_category_choice = profile_options.id
			LEFT JOIN profile_category ON profile_category.id = profile_options.profile_category_id
			LEFT JOIN user_profile as other_users_profile ON user_profile.profile_category_choice = other_users_profile.profile_category_choice AND user_profile.user_info_id != other_users_profile.user_info_id
			LEFT JOIN user_opinion on user_opinion.user_info_id = other_users_profile.user_info_id and (user_opinion.scene_user_like = 1 or (user_opinion.scene_user_interest = 1 and user_opinion.scene_user_like != 0))
			WHERE user_profile.user_info_id = 15";
		$this->dbc->query($validate );//, array($_POST['userId']));
		$profiles = $this->dbc->fetch_all_assoc();

/*
block of code starts the keyword search for suggestions.
		$output = array();
		foreach ($aroundMe as $location) {
			//if, ){
			foreach ($profile as $profile) {
				$output[] = $this->findCommonalities($location, $profile);
			}
			//}
		}

		*/
		return $aroundMe;
	}

	function findCommonalities($location, $profile){
		foreach ($location as $key => $value) {
			
		}
	}

	function recursive_array_search(&$array, $term){
		$occurances = 0;
		return $this->recursive_array_search_helper($array, $term, $occurances);
	}

	function recursive_array_search_helper( &$array, $term, &$occurances ){
		foreach ($array as $value) {
			if(is_array($value)){
				$this->recursive_array_search_helper($value, $term, $occurances);
				continue;
			}
			if($value==$term){
				$occurances++;
			}
		}

		return $occurances;
	}

	/**
	* Function that queries database and
	* returns an array of tuples where the 
	* user is a current admin.
	* @author Eva Kuntz
	**/
	function view_current_admins(){
		$select = "SELECT user_info_id,name,email_address,status FROM admin_info WHERE status=?";

		$this->dbc->query($select,array(1));
		$this->echoJson(true,$this->dbc->fetch_all_assoc());
		return;
	}

	/**
	* Function that queries and
	* returns all of the admins
	* that are pending admin access in
	* the admin_info db table.
	* @author Eva Kuntz
	**/
	function view_pending_admins(){
		$select = "SELECT user_info_id,name,email_address,status FROM admin_info WHERE status=?";

		$this->dbc->query($select,array(0));
		$this->echoJson(true,$this->dbc->fetch_all_assoc());
		return;
	}


	/**
	* Function that queries and
	* returns all of the admins
	* that were denied admin access in
	* the admin_info db table.
	* @author Eva Kuntz
	**/
	function view_denied_admins(){
		$select = "SELECT user_info_id,name,email_address,status,comment FROM admin_info WHERE status=?";

		$this->dbc->query($select,array(2));
		$this->echoJson(true,$this->dbc->fetch_all_assoc());
		return;
	}

	/**
	* Function that queries and
	* returns all of the tuples in
	* the admin_info db table.
	* @author Eva Kuntz
	**/
	function view_all_admins(){
		$select = "SELECT user_info_id,name,email_address,status FROM admin_info";

		$this->dbc->query($select,null);
		$this->echoJson(true,$this->dbc->fetch_all_assoc());
		return;
	}


	/**
	* Function that updates a pending
	* admin's account status.
	* If approved, admin account is inserted into
	* user_info db table and they assigned an actual
	* account id.
	* If denied, a comment is inserted into database
	* describing why the admin was denied.
	* @author Eva Kuntz
	**/
	function update_pending_admin(){
		$email_address = htmlspecialchars($_POST["admin_email"]);
		$new_status = htmlspecialchars($_POST["new_status"]);

		if(isset($_POST["comment"])){ $comment = htmlspecialchars($_POST["comment"]);}

		//if new status is APPROVED, add admin to the regular user_info db table
		//get new user_info_id as well
		if($new_status == 1){
			$select = "SELECT * FROM admin_info WHERE email_address=?";
			$this->dbc->query($select, array($email_address));
			$result = $this->dbc->fetch_all_assoc()[0];

			//admin account type = 3
			//insert admin into the official user_info db table
			$this->insert_into_user_info($result["name"], NULL, $email_address, $result["password"], 3);
			
			//query user_info to get the actual user id
			$select = "SELECT id FROM user_info WHERE email_address=?";
			$this->dbc->query($select,array($email_address));
			$user_info_id = $this->dbc->fetch_all_assoc()[0]["id"];

			$update = "UPDATE admin_info SET user_info_id=?,status=? WHERE email_address=?";
			$this->dbc->query($update,array($user_info_id,$new_status,$email_address));

			$this->echoJson(true,null);

		}else{ //admin status was deined
			$update = "UPDATE admin_info SET status=?,comment=? WHERE email_address=?";
			$this->dbc->query($update,array($new_status,$comment,$email_address));
			$this->echoJson(true,null);
		}
	}

	function view_all_company_users(){
		$select = "SELECT * FROM company_info";
		$this->dbc->query($select,null);

		$this->echoJson(true,$this->dbc->fetch_all_assoc());
	}

	function view_pending_company_users(){
		$select = "SELECT * FROM company_info WHERE status=?";

		$this->dbc->query($select, array(0));

		$this->echoJson(true,$this->dbc->fetch_all_assoc());
	}

	function view_denied_company_users(){
		$select = "SELECT * FROM company_info WHERE status=?";

		$this->dbc->query($select, array(2));

		$this->echoJson(true,$this->dbc->fetch_all_assoc());
	}

	function view_current_company_users(){
		$select = "SELECT * FROM company_info WHERE status=?";

		$this->dbc->query($select, array(1));

		$this->echoJson(true,$this->dbc->fetch_all_assoc());
	}

	function update_pending_company_user(){
		$email_address = htmlspecialchars($_POST["company_email"]);
		$new_status = htmlspecialchars($_POST["new_status"]);

		if(isset($_POST["comment"])){ $comment = htmlspecialchars($_POST["comment"]);}

		//if new status is APPROVED, add admin to the regular user_info db table
		//get new user_info_id as well
		if($new_status == 1){
			$select = "SELECT * FROM company_info WHERE email_address=?";
			$this->dbc->query($select, array($email_address));
			$result = $this->dbc->fetch_all_assoc()[0];

			//company account type = 2
			//insert admin into the official user_info db table
			$this->insert_into_user_info($result["company_name"], NULL, $email_address, $result["password"], 2);
			
			//query user_info to get the actual user id
			$select = "SELECT id FROM user_info WHERE email_address=?";
			$this->dbc->query($select,array($email_address));
			$user_info_id = $this->dbc->fetch_all_assoc()[0]["id"];

			$update = "UPDATE company_info SET user_info_id=?,status=? WHERE email_address=?";
			$this->dbc->query($update,array($user_info_id,$new_status,$email_address));

			$this->echoJson(true,null);

		}else{ //admin status was deined
			$update = "UPDATE company_info SET status=?,comment=? WHERE email_address=?";
			$this->dbc->query($update,array($new_status,$comment,$email_address));
			$this->echoJson(true,null);
		}
	}

}
?>
