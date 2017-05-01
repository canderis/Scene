<?php
require_once('api_base_class.php');

class fandango_api extends api_base_class{

	private $FANDANGO_API_KEY	=	'zxwapp6actstvpzegqrtazn8';
  private $SECRET_KEY = '4F98pBNShE';
  private $RADIUS = 10;

	function __construct($latitude, $longitude){
		$this->raw_data_object = $this->fandango_search($latitude, $longitude);
	}

  function fandango_search($latitude, $longitude){
    $url = "http://api.fandango.com/v1/?op=moviesbylatlonsearch&lat=".$latitude."&lon=".$longitude."&radius=".$this->RADIUS."&apikey=".$this->FANDANGO_API_KEY."&sig=e77ee2b77a06f3f80a06147633aae98f56f8542aabd33186ab80c12f5fc4aefe";
    $response = json_decode($this->httpGet($url));
    print_r(json_decode(json_encode($response),true));
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
		//echo "<pre>";
		//print_r( $this->raw_data_object);
		//echo "</pre>";
		for($i=0; $i<count($this->raw_data_object['businesses']); $i++){
			if(sizeof($this->raw_data_object["businesses"][$i]["location"]["display_address"]) === 2)
			{
				$temp =
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][0] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][1];
			}
			else
			{
				$temp =
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][0] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][1] .
				$this->raw_data_object["businesses"][$i]["location"]["display_address"][2];
			}
			$temp = $this->stripExtraCharacters($temp);
			$this->prepped_data_array[$temp] = array(
				"rating_y"=>
					$this->raw_data_object["businesses"][$i]["rating"],
				"name_y"=>
					$this->raw_data_object["businesses"][$i]["name"],
				"categories_y"=>array
				(
					"first_y"=>array
					(	"0"=>
						$this->raw_data_object["businesses"][$i]["categories"][0][0],
						"1"=>
						$this->raw_data_object["businesses"][$i]["categories"][0][1]
					)
				),
				"snippet_text_y"=>
					$this->raw_data_object["businesses"][$i]["snippet_text"],
				"image_url_y"=>
					$this->raw_data_object["businesses"][$i]["image_url"],
				// "snippet_image_url_y"=>
				// 	$this->raw_data_object["businesses"][$i]["snippet_image_url"],
				"display_phone_y"=>
					$this->raw_data_object["businesses"][$i]["display_phone"],
				"id_y"=>
					$this->raw_data_object["businesses"][$i]["id"],
				"is_closed_y"=>
					$this->raw_data_object["businesses"][$i]["is_closed"],
				"location_y"=>array
				(
					"city_y"=>
						$this->raw_data_object["businesses"][$i]["location"]["city"],
					"postal_code_y"=>
						$this->raw_data_object["businesses"][$i]["location"]["postal_code"],
					"country_code_y"=>
						$this->raw_data_object["businesses"][$i]["location"]["country_code"],
					"address_y"=>
						$this->raw_data_object["businesses"][$i]["location"]["address"][0],
					"coordinate_y"=>array
					(
						"lat_y"=>
							$this->raw_data_object["businesses"][$i]["location"]
							["coordinate"]["latitude"],
						"lon_y"=>
							$this->raw_data_object["businesses"][$i]["location"]
							["coordinate"]["longitude"]
					),
					"state_code_y"=>
						$this->raw_data_object["businesses"][$i]["location"]["state_code"]
				));
		}
}


	/**
	* Function that performs a Yelp Search. Takes in the latitude and longitude of a location and appends it to the url
	* Calls yelp_request
	* @param int latitude
	* @param int longitude
	* @return string Json format of data
	* @author Sydney Ehlinger
	*/
	public function yelp_search($latitude, $longitude) {
		$url_params = array();
		$location = $this->getLocationFromLadLong($latitude, $longitude);
		return;
	//	$url_params['cll'] = $latitude.','.$longitude;
	//	$search_path = '/v2/search/?' . http_build_query($url_params);
	//	return json_decode(json_encode($this->yelp_request($search_path)),true);
	}




}
?>
