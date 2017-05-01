<?php
require_once('api_base_class.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/php/vendor/autoload.php');

class movie_database_api extends api_base_class{

	private $API_KEY	=	'6a9b2893c3e81526150d7c5fa9ca169f';

	function __construct(){
		$this->base_class_id = "MOVIE";
    	$this->raw_data_object = $this->movie_database_search();
		$this->raw_data_object = json_decode($this->raw_data_object["body"],true)["results"];
		$this->prep_api_array();
		Requests::register_autoloader();
	}

  function movie_database_search(){
    $url = "https://api.themoviedb.org/3/movie/now_playing?api_key=".$this->API_KEY."&language=en-US&page=1";
	$response = Requests::get($url, array(), NULL);
	return json_decode(json_encode($response), TRUE);
  }

  public function prep_api_array(){
		for($i = 0; $i < count($this->raw_data_object); $i++){
			$temp = $this->raw_data_object[$i]['title'];
			$temp = $this->stripExtraCharacters($temp);
			$this->prepped_data_array[$temp] = array(
					"title"=>
						$this->raw_data_object[$i]["title"],
					"description"=>
						$this->raw_data_object[$i]["overview"],
					"rating" =>
						$this->raw_data_object[$i]["vote_average"],
					"poster" =>
						$this->raw_data_object[$i]["poster_path"],
					"id_m"=>
						$this->getId()
			);
  	}
	}
}
?>
