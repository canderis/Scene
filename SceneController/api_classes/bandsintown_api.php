<?php
//mapquest_api.php
require_once('api_base_class.php');
class bandsintown_api extends api_base_class{

	private $api_url = "http://api.bandsintown.com/artists/";
	private $format = "json"; //preferred format as usuall :)
	private $api_version = "2.0"; // this is the latest version.
	private $app_id = "Scene"; // app_id can be any string.

		function __construct($artist_name){
			//$this->prep_api_array();
			$this->eventsByArtist($artist_name);
		}

		public function prep_api_array(){
		}

		public function eventsByArtist($artist_name){

			// We validate first that we received an artist name, if not return FALSE
			if( empty($artist_name) )return FALSE;
			// It is important to avoid all strange characters in the artist's name
			$artist_name = $this->parseName($artist_name);

			$url = "{$this->api_url}{$artist_name}/events.{$this->format}?api_version={$this->api_version}&app_id={$this->app_id}";

	 		// Connect through cURL
	 		$link = curl_init();
	 		curl_setopt($link, CURLOPT_URL, $url );
	 		curl_setopt($link, CURLOPT_HEADER, FALSE);
	 		curl_setopt($link, CURLOPT_RETURNTRANSFER, true);
	 		$response = curl_exec($link);
	 		curl_close($link);

			print_r($response);
	 	}

	 	// This function parses the artist's name to avoid non standard characters.
	 	public function parseName( $name ){
	 		$name = chop($name);
	 		$name = strtolower($name);
	 		$name = str_replace(array('á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ','"',' ','-'), array('a','e','i','o','u','a','e','i','o','u','n','','%20',''), $name);
	 		return $name;
	 	}

}

?>
