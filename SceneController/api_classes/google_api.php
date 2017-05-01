<?php
require_once('api_base_class.php');
/**
* Google API Class
* Contains functions and constructor
* related to the Google API searches.
* @author Kevin Mathes
**/
class google_api extends api_base_class
{
	private $GOOGLE_API_KEY  = "AIzaSyBFCiA3snjrmOplVEvDnH57uX3xW6Zz-tc";
	private $RADIUS          = "10000"; //in meters
	private $TYPE            = "restaurant";
	//private $KEYWORD         = "cruise";//dont need it

	/**
	* Constructor is first called when the google_api is called
	* Calls prep_api_array to prepare raw_data_object. See api_base_class.php for 
	* more info
	* Calls google_search
	* Sets the base_class_id to "id_g" specifically for google
	* @param int latitude
	* @param int longitude
	* @author Kevin	Mathes
	*/
	function __construct($latitude, $longitude)
	{
		$this->base_class_id = "id_g";
		$this->raw_data_object = $this->google_search($latitude, $longitude);
		$this->raw_data_object = json_decode($this->raw_data_object["body"],true)["results"];
		$this->prep_api_array();
		Requests::register_autoloader();
	}

	function google_search($latitude, $longitude)
	{	
		$url = 
		"https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitude.",".$longitude."&radius=".$this->RADIUS."&type=".$this->TYPE."&key=".$this->GOOGLE_API_KEY;

		$response = Requests::get($url, array(), NULL);
		return json_decode(json_encode($response), TRUE);
	}

	function prep_api_array()
	{
		$this->prepped_data_array = null;
		for($i = 0; $i < count($this->raw_data_object); $i++)
		{
			//fix the hard coded IA
			$temp = $this->raw_data_object[$i]["vicinity"] . "IA";
			$temp = $this->stripExtraCharacters($temp);
			$this->prepped_data_array[$temp] = array
			(

				"latitude_g" =>
					$this->raw_data_object[$i]["geometry"]["location"]["lat"],
				"longitude_g" =>
					$this->raw_data_object[$i]["geometry"]["location"]["lng"],
				"id_g" =>
					"GOOGLE",
				"name_g" =>
					$this->raw_data_object[$i]["name"],
				"rating_g" =>
					$this->raw_data_object[$i]["rating"],
				"categories_g"=>
					$this->raw_data_object[$i]["types"],
				"address_g" =>
					$this->raw_data_object[$i]["vicinity"]
			);		
		}
	}
}
//https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=42.026802,-93.620181&radius=500&type=restaurant&key=AIzaSyBFCiA3snjrmOplVEvDnH57uX3xW6Zz-tc

?>