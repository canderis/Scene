<?php
/**
* api_base_class.php
* api_base_class is an abstract class that provides helper funtions used for talking to various APIs and
* defines functions Scene.php relies on existing for prepairing a data object for the client.
*
* This was created to modularize the API calls, allowing them to be added easier without breaking existing code
* or requiring extensive modification.
*
* The construct funtion should prepare the $raw_data_object before the prep_api_array is called.
*
* @author Scott Huffman.
**/
abstract class api_base_class{
	//private $latitude;
	//private $longitude;

	/**
	* $raw_data_object is a store for whatever data the API passes back
	**/
	protected $raw_data_object;
	/**
	* $prepped_data_array is a store data processed and formatted appropriatly.
	**/
	protected $prepped_data_array;
	/**
	* $base_class_id holds a unique id for each child class of the api_base_class
	**/
	protected $base_class_id;

	//the construct funtion should prepare the $raw_data_object before the prep_api_array is called.
	//$raw_data_object is what is returned from the api call.
	//function __construct($latitude, $longitude){

	//	$this->prep_API_array();
	//}

	/**
	* this function is called by the constructor.
	* this function should prepare the associative array, mapped using the address, with the relevant data.
	**/
	protected abstract function prep_api_array();

	/**
	* Getter funtion for prepped_data_array.
	* @author Scott Huffman
	* @return array prepped_data_array
	**/
	public function get_prepped_data_array() {
		return $this->prepped_data_array;
	}
	
	/**
	* Function that makes httpGet request from php (server)
	* using CURL
	* @author Eva Kuntz
	* @param string url to make GET request too
	* @return string response from the GET request
	**/
	protected function httpGet($url){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$resp = curl_exec($curl);

		curl_close($curl); //close curl services->consider keeping open all the time???
		return $resp; //return the response
	}
	
	
	/**
	* Function to strip extra white space, commas, and set to lower case.
	* @author Kevin Mathes
	* @param string a string or key that needs to be formatted 
	* @return string a properly formatted string
	**/
	protected function stripExtraCharacters($string)
	{
		$string = preg_replace("/(\W)+/", '', $string);
		$string = strtolower($string);
		return $string; 
	}

	/**
	* Function to return the base class id
	* @author Kevin Mathes 
	* @return base_class_id string
	**/
	protected function getId()
	{
		return $this->base_class_id;
	}

	/**
	* A function that will take a number of api arrays (to be specified later) in 
	* order to create on master array of events or locations. This function takes 
	* the different data segments from each api and will create space for all of 
	* them in the master array. Any element that cannot be found will be set to 
	* null. 
	* @author Kevin Mathes
	* @param array an array of api_base_class objects
	* @return array an array master array containing all necessary data
	**/
	public static function mergeApiArrays($api_base_classes)
	{	
		$result = array(); 

		foreach($api_base_classes as $array)
		{
			$array = $array->get_prepped_data_array();
			$result = array_merge_recursive($result,$array);
		}

		foreach($api_base_classes as $array)
		{
			$k = $array->getId();
			//print_r($k);
			//return;
			foreach($result as $key=>$value)
			{
				if(!isset($value[$k]))
				{
					unset($result[$key]);
				}
			}
		}

		return $result;
	}
}

?>