<?php
require_once('api_base_class.php');
/**
* Calls authorization file which is used when requesting the search to authorize the user
*/
require_once($_SERVER['DOCUMENT_ROOT'] . '/SceneController/YelpAuth/OAuth.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/php/vendor/autoload.php');


/**
* Yelp API Class
* Contains functions and constructor
* related to the Yelp API searches.
* @author Sydney Ehlinger
**/
class yelp_api extends api_base_class{

	public function getRaw(){
		return $this->raw_data_object;
	}
	/**
	* Variable keys are used for authorization of the user's api call
	* @var CONSUMER_KEY - string
	* @var CONSUMER_SECRET - string
	* @var TOKEN - string
	* @var TOKEN_SECRET - string
	* @author Sydney Ehlinger
	*/
	private $CONSUMER_KEY	=	'VLtyFlbeDJ-L1EOwg6tc_Q';
	private $CONSUMER_SECRET = 'fjW_tfDRTrTAYTs3IepOu6CYBWY';
	private $TOKEN = '-_f8dQCu58m3c0sO5T13cAuNZAzcA8Vi';
	private $TOKEN_SECRET = 'bLTTPaolUb21UgL-RrUzymjDp5k';
	private $GOOGLE_API_KEY = 'AIzaSyBUJTyWW1pV9RLDQ8xW78jtW4nBXASUyac';

	//private $REQUEST_OBJECT = new Requests();

	/**
	* Constructor is first called when the yelp_api is called
	* Calls prep_api_array to prepare raw_data_object. See api_base_class.php for more info
	* Calls yelp_search
	* @param int latitude
	* @param int longitude
	* @author Sydney Ehlinger
	*/
	function __construct($latitude, $longitude){
		$this->base_class_id = "id_y";
		$this->raw_data_object = $this->yelp_search($latitude, $longitude);
		$this->prep_api_array();
		Requests::register_autoloader();
	}

	/**
	* Function that prepares raw data and puts it into a prepped array. Takes
	* the address elements and parses them into a key that will be used to
	* represent an event or location, in an associative array. Then adds each
	* element that is deemed important inside of the array with its own key.
	* Different cases may be used depending upon the what api requires
	* Nothing is returned or taken as parameters, just modified.
	* @author Kevin Mathes
	**/
	public function prep_api_array(){
		$this->prepped_data_array = null;
		
		for($i=0; $i<count($this->raw_data_object['businesses']); $i++){
			if(sizeof($this->raw_data_object["businesses"][$i]["location"]["display_address"]) === 2)
			{
				$temp =
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][0] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][1] ;
			}
			else
			{
				$temp =
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][0] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][1] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][2] ;
			}
			$temp = $this->stripExtraCharacters($temp);
			$extra = array();
			if(isset($this->raw_data_object["businesses"][$i]["rating"]))
			{
				$extra["rating_y"] = 
					$this->raw_data_object["businesses"][$i]["rating"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["name"]))
			{
				$extra["name_y"] = 
					$this->raw_data_object["businesses"][$i]["name"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["categories"]))
			{
				$extra["categories_y"] = array
					(
						"0"=> array 
						(
							"0"=>
						$this->raw_data_object["businesses"][$i]["categories"][0][0],
							"1"=>
						$this->raw_data_object["businesses"][$i]["categories"][0][1]
						)
					);
			}
			if(isset($this->raw_data_object["businesses"][$i]["snippet_text"]))
			{
				$extra["snippet_text_y"] =
					$this->raw_data_object["businesses"][$i]["snippet_text"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["image_url"]))
			{
				$extra["image_url_y"] =
					$this->raw_data_object["businesses"][$i]["image_url"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["display_phone"]))
			{
				$extra["display_phone_y"] =
					$this->raw_data_object["businesses"][$i]["display_phone"];
			}
			$extra["id_y"] = "YELP";
			if(isset($this->raw_data_object["businesses"][$i]["is_closed"]))
			{
				$extra["is_closed_y"] =
					$this->raw_data_object["businesses"][$i]["is_closed"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["snippet_text"]))
			{
				$extra["snippet_text_y"] =
					$this->raw_data_object["businesses"][$i]["snippet_text"];
			}
			if(isset($this->raw_data_object["businesses"][$i]["location"]))
			{
				$extra["location_y"] = array();
				if(isset($this->raw_data_object["businesses"][$i]["location"]["city"]))
				{
					$extra["location_y"]["city_y"]=
						$this->raw_data_object
							["businesses"][$i]["location"]["city"];
				}
				if(isset($this->raw_data_object["businesses"][$i]["location"]["postal_code"]))
					$extra["location_y"]["postal_code_y"]=
						$this->raw_data_object
							["businesses"][$i]["location"]["postal_code"];
				if(isset($this->raw_data_object["businesses"][$i]["location"]["country_code"]))
					$extra["location_y"]["country_code_y"]=
						$this->raw_data_object
							["businesses"][$i]["location"]["country_code"];
				if(isset($this->raw_data_object["businesses"][$i]["location"]["address"][0]))
					$extra["location_y"]["address_y"]=
						$this->raw_data_object
							["businesses"][$i]["location"]["address"][0];
				if(isset($this->raw_data_object["businesses"][$i]["location"]["coordinate"]))
					$extra["location_y"]["coordinate_y"]= array
					(
						"lat_y"=>
							$this->raw_data_object
								["businesses"][$i]["location"]
								["coordinate"]["latitude"],
						"lon_y"=>
							$this->raw_data_object
								["businesses"][$i]["location"]
								["coordinate"]["longitude"]
					);
				if(isset($this->raw_data_object["businesses"][$i]["location"]["state_code"]))
					$extra["location_y"]["state_code_y"]=
						$this->raw_data_object
							["businesses"][$i]["location"]["state_code"];		
			}
			$this->prepped_data_array[$temp] = $extra;
		}
	}


	/**
	* Function that performs a Yelp Search. Takes in the latitude and longitude of a location and appends it to the url
	* Calls yelp_request
	* @param int latitude
	* @param int longitude
	* @return string Json format of data
	* @author Sydney Ehlinger & Eva Kuntz
	*/
	public function yelp_search($latitude, $longitude) {
		$url_params = array();

		//get the location from the lat/lng pair
		$location = $this->getLocationFromLadLong($latitude, $longitude);
		
		//set url params
		$url_params["location"] = $location;
		$url_params['cll'] = $latitude.','.$longitude;
		$url_params["radius_filter"] = "10000";
		$url_params["limit"] = "40";
		$search_path = '/v2/search/?' . http_build_query($url_params);

		return json_decode(json_encode($this->yelp_request($search_path)),true);
	}


	/**
	* Function get location based off of latitude and longitude.
	* @param int latitude
	* @param int longitude
	* @return string location
	* @author Sydney Ehlinger & Eva Kuntz
	*/
	private function getLocationFromLadLong($latitude, $longitude){
		$url_params['latlng'] = $latitude.','.$longitude;
		$url_params['key'] = $this->GOOGLE_API_KEY;
		$search_path = 'https://maps.googleapis.com/maps/api/geocode/json?'. http_build_query($url_params);		
		
		try{
			$str = Requests::get($search_path, array('Accept' => 'application/json'));
		}
		catch(Exception $e){ }		

		//convert from Results_Response object to array
		$json = json_decode(json_encode($str), true);

		//get the actual results index
		$json = json_decode($json["body"], true);

		//get the location (city name) and return
		$location = $json['results'][0]['address_components'][2]['long_name'];
		return $location;
	}

	/**
	* Function that creates a Yelp Request which checks the keys and authorizes the search.
	* @param string End of url that constains the latitude and longitude of the location.
	* @return string json of data
	* @author Sydney Ehlinger
	*/
	private function yelp_request($path) {
		$unsigned_url = "http://api.yelp.com". $path;
		//print_r($unsigned_url);
	  // Token object built using the OAuth library
	  $token = new OAuthToken($this->TOKEN, $this->TOKEN_SECRET);
	  // Consumer object built using the OAuth library
	  $consumer = new OAuthConsumer($this->CONSUMER_KEY, $this->CONSUMER_SECRET);
	  // Yelp uses HMAC SHA1 encoding
	  $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
	  $oauthrequest = OAuthRequest::from_consumer_and_token(
	  		$consumer,
	      $token,
	      'GET',
	      $unsigned_url
	  );
	  // Sign the request
	  $oauthrequest->sign_request($signature_method, $consumer, $token);
	  // Get the signed URL
	  $signed_url = $oauthrequest->to_url();
		//print_r($signed_url);
	  // Send Yelp API Call
	  try {
	      $ch = curl_init($signed_url);
	      	if (FALSE === $ch)
	        	throw new Exception('Failed to initialize');
	      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	      curl_setopt($ch, CURLOPT_HEADER, 0);
	      $data = curl_exec($ch);
	      if (FALSE === $data)
	          throw new Exception(curl_error($ch), curl_errno($ch));
	      $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	      if (200 != $http_status)
	          throw new Exception($data, $http_status);
	      curl_close($ch);
	  } catch(Exception $e) {
	      trigger_error(sprintf(
	      		'Curl failed with error #%d: %s',
	        	$e->getCode(), $e->getMessage()),
	          E_USER_ERROR);
	  }
	  return json_decode($data);
	}

}
?>
