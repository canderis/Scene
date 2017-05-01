<?php
//mapquest_api.php
require_once('api_base_class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/php/vendor/autoload.php');
/**
* Mapquest API Class
* Contains functions and constructor
* related to the Mapquest API searches.
* @author Eva Kuntz
**/
class mapquest_api extends api_base_class{
	
	/*Mapquest API key*/
	private $MAPQUEST_API_KEY = 'UzKbgBQyuWXxCdqz2qxcNvkCqzPpmJby';
	
	/*Attribute for mapquest object that holds the sic_codes for various
	categories used to search for different events*/
	private $MAPQUEST_CATEGORIES = array(581208,20841/*,541101*/,543102,546103,572233,581203,581206,581214,
	581226,581228,581301,581305,581307,581309,783201,799951,841201,581208);
	
	/*Index variable for merging results*/
	private $index = 0;
	
	
	/**
	* Constructor for MapQuest API object
	*
	* Takes a latitude and longitude argument
	* and performs a mapquest search based on the lat/lng
	* arguments given.
	* 
	* @author Eva Kuntz
	* @param int latitude
	* @param $int longitude
	* @return void
	**/
	function __construct($latitude, $longitude){
		//do prep-work here to prepare raw_data_object. See api_base_class.php for more info.
		$this->base_class_id = "id_m";
		$this->raw_data_object = $this->mapQuestSearch(null, $latitude, $longitude);
		$this->prep_api_array();
	}
	
	/**
	* Function that merges the filtered results from
	* the mapquest nearby search.
	* Makes sure resulting array is in a usable form
	* @author Eva Kuntz
	* @return void
	**/
	public function merge_prepped_array(){
		$result = NULL;
		for($i = 0; $i < $this->index; $i++){
			foreach($this->prepped_data_array[$i] as $value){
				$result[key($value)] = $value;
				//var_dump($value);
				//echo "<p></p>";				
			}
		}		
		$this->prepped_data_array = $result;
	}

	/**
	* Function that prepares raw data and filters each mapquest
	* search array. If search did not return any results,
	* set null and do not include in the final, prepped data
	* array. Otherwise, filter and store the prepped array, before merging
	* results into the prepped data array object.
	* Nothing is returned or taken as parameters, just modified.
	* @author Kevin Mathes & Eva Kuntz
	* @return void
	**/
	public function prep_api_array()
	{
		foreach($this->raw_data_object as $array)
		{
			//if result count is zero, return null and do NOT filter results
			if($array["resultsCount"] == 0)
			{
				$result = null;	
			}	
			else
			{
				//else, filter results
				$result = null;
				$result_count = $array["resultsCount"];
	
				for($i=0; $i < $result_count; $i++)
				{
					$temp = $array["searchResults"][$i]["fields"]["address"] .
							$array["searchResults"][$i]["fields"]["city"] .
							$array["searchResults"][$i]["fields"]["state"] .
							$array["searchResults"][$i]["fields"]["postal_code"]
							;
					$temp = $this->stripExtraCharacters($temp);
					$uni = $array["searchResults"][$i]["name"];
					$uni = $temp . $this->stripExtraCharacters($uni);
					$result[$temp] = array(
					"uniqueId"=>
						$uni,
					"distanceUnit"=>
						$array["searchResults"][$i]["distanceUnit"],
					"distance"=>
						$array["searchResults"][$i]["distance"],
					"name"=>
						$array["searchResults"][$i]["name"],
					"address"=>
						$array["searchResults"][$i]["fields"]["address"],
					"city"=>
						$array["searchResults"][$i]["fields"]["city"],
					"state"=>
						$array["searchResults"][$i]["fields"]["state"],
					"postal_code"=>
						$array["searchResults"][$i]["fields"]["postal_code"],
					"id_m"=> 
						"MAPQUEST", 
						//$array["searchResults"][$i]["fields"]["id"],
					"phone"=>
						$array["searchResults"][$i]["fields"]["phone"]);
				}
			}	
			if($result !== null)
			{
				$this->prepped_data_array[$this->index] = $result;
				$this->index++;
			}
		}	
		$this->modifyPrepped();
	}

	/**
	* Function to make the prepped data array go from 2d to 1d
	* 1 dimension is the proper way needed for array merge 
	* @author Kevin Mathes
	* @return void 
	**/
	private function modifyPrepped()
	{
		$temp = array();
		foreach($this->prepped_data_array as $array)
		{
			foreach($array as $key => $event)
			{
				$temp[$key] = $event; 
			}

		}
		$this->prepped_data_array = $temp;
	}
	
	/**
	* Function to validate location and format location.
	* @author Eva Kuntz
	* @param string location to format
	* @return string formatted location
	**/
	private function validateLocation($location){
		//add more things here: validating location->make sure location exists, correct format, no white space
		return preg_replace('/\s+/', '', $location);
	}
	
	
	/**
	* Uses a get request to convert a
	* location (string) into a latitude and longitude
	* object.
	* @author Eva Kuntz
	* @param string location to geocode
	* @return array latitude and longitude
	**/
	private function getLatLng($location){
		//request sent to MapQuest API for geocoding
		$obj = json_decode($this->httpGet("http://www.mapquestapi.com/geocoding/v1/address?key=".$this->MAPQUEST_API_KEY."&location=".$location."&thumbMaps=false"), true);
		return $obj["results"][0]["locations"][0]["latLng"]; //return lat/lng object
	}
	
	/**
	* Function that returns this mapquest object api key
	* @author Eva Kuntz
	* @return string mapquest api key
	**/
	private function get_mapquest_api_key(){
		return $this->$MAPQUEST_API_KEY;
	}
	
	/**
	* Function that uses the map quest api to do a nearby search based on either a location (string) or a latitude and longitude pair. Also performs searches
	* based on mapquest sic_code categories.
	* @author Eva Kuntz
	* @param string location (optional if using lat/long pair)
	* @param int lat (latitude)
	* @param int lng (longitude)
	* NOTE: use either location or lat/lng pair
	* @return string json object of search results
	**/
	private function mapQuestSearch($location, $lat, $lng){
		$response = [];

	//	$config = HTMLPurifier_Config::createDefault();
	//	$purifier = new HTMLPurifier($config);
		//$clean_html = $dirty_html);
		
		for($i = 0; $i < sizeof($this->MAPQUEST_CATEGORIES); $i++){		
			$url = "";		
			if(!$lat || !$lng || strlen(trim(utf8_decode($lat))) < 1 || strlen(trim(utf8_decode($lng))) < 1){ //if lat/lng pair does not exist, use location
			$location = $this->validateLocation($location); //format location
			$url = "http://www.mapquestapi.com/search/v2/radius?origin=".$location."&radius=5&maxMatches=10&ambiguities=ignore&hostedData=mqap.ntpois|group_sic_code=?|".$this->MAPQUEST_CATEGORIES[$i]."&outFormat=json&key=".$this->MAPQUEST_API_KEY."&thumbMaps=false";
				
			}else if(!$location || strlen(trim(utf8_decode($location))) < 1){ //otherwise, location var does not exist and use the lat/lng pair
				$lat = $this->validateLocation($lat);
				$lng = $this->validateLocation($lng);
				
				$url = "http://www.mapquestapi.com/search/v2/search?key=".$this->MAPQUEST_API_KEY."&maxMatches=10&shapePoints=".$lat.",".$lng."&thumbMaps=false&hostedData=mqap.ntpois|group_sic_code=?|".$this->MAPQUEST_CATEGORIES[$i];			
			}		
			$response[$i] = json_decode($this->httpGet($url)); //$purifier->purify($this->httpGet($url))); //5mile radius; max 10 results
		}
		
		return json_decode(json_encode($response),true);		
	}
	
	/**
	* Function that reverses a lat/lng pair
	* and returns the address of the lat/lng pair
	* using reverse geocoding.
	* @author Eva Kuntz
	* @param int lat (latitude)
	* @param int lng (longitude)
	* @return string location in json object format
	**/
	private function reverseGeocode($lat,$lng){	
		$url = "http://www.mapquestapi.com/geocoding/v1/reverse?key=".$this->MAPQUEST_API_KEY."&location=".$lat."%2C".$lng."&thumbMaps=false";
		$response = json_decode($this->httpGet($url));		
		return $response; //json_encode($response);
	}	
	
		
	/**
	* Function that takes a category (string), formats the 
	* string, then converts the string to a mapquest api search category (sic_code used in a get request to the mapquest api).
	* @author Eva Kuntz
	* @param string category (default is resturant category)
	* @return int 6-digit sic_code
	**/
	private function convert_category_to_sic_code($category){
		$category = strtolower($category); //convert to lowercase
		$sic_code = 581208;
		if($category === "wineries"){
			$sic_code = 20841;
		}else if($category === "food market"){
			$sic_code = 541101;
		}else if($category === "farm markets"){
			$sic_code = 543102;
		}else if($category === "bakers cake & pie"){
			$sic_code = 546103;
		}else if($category === "grills barbecue"){
			$sic_code = 572233;
		}else if($category === "ice cream parlors"){
			$sic_code = 581203;
		}else if($category === "foods carry out"){
			$sic_code = 581206;
		}else if($category === "cafe"){
			$sic_code = 581214;
		}else if($category === "theatres dinner"){
			$sic_code = 581226;
		}else if($category === "coffee shops"){
			$sic_code = 581228;
		}else if($category === "bars"){
			$sic_code = 581301;
		}else if($category === "pubs"){
			$sic_code = 581305;
		}else if($category === "comedy clubs"){
			$sic_code = 581307;
		}else if($category === "karaoke clubs"){
			$sic_code = 581309;
		}else if($category === "movie theatres"){
			$sic_code = 783201;
		}else if($category === "water parks"){
			$sic_code = 799602;
		}else if($category === "amusement parks"){
			$sic_code = 799604;
		}else if($category === "zoos"){
			$sic_code = 842201;
		}else if($category === "parks"){
			$sic_code = 799951;
		}else if($category === "museums"){
			$sic_code = 841201;
		}else if($category === "planetariums"){
			$sic_code = 841204;
		}else if($category === "beach"){
			$sic_code = 901006;
		}else{
			//(all) resturats
			$sic_code = 581208;
		}
	}
}
?>