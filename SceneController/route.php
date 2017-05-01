<?php
//echo "hi";

	include("scene.php");
	$app = new scene();

	if(FALSE === $lastSlash = strrpos($_SERVER['REQUEST_URI'],'/')){
		//serve404error("no slashes to route!");
		echo "404 error. Check /'s'. No /'s ";
		die;
	}

	if(2 !== substr_count($_SERVER['REQUEST_URI'],'/')){
		echo "404 error. Check /'s'.";
		die;
	}


	//$class = substr($_SERVER['REQUEST_URI'],1,$lastSlash-1);
	//$path = $_SERVER['DOCUMENT_ROOT'] . '/'.$class.'/';

	if(FALSE !== $queryPos = strrpos($_SERVER['REQUEST_URI'],'?')){
		$routeMethod = substr($_SERVER['REQUEST_URI'], $lastSlash+1, $queryPos-$lastSlash-1);
	}else{
		$routeMethod = substr($_SERVER['REQUEST_URI'], $lastSlash+1);
	}


	$app->$routeMethod();

	//echo "hi";
	// $_SERVER['REQUEST_URI'];
	//if($_SERVER['REQUEST_URI'] === "/")
		//file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/SceneUI/index.html");
	//include($_SERVER['DOCUMENT_ROOT'] . "/SceneUI" . $_SERVER['REQUEST_URI']);
	/*if(strrpos($_SERVER['REQUEST_URI'], "/inc/" ) ){
		include($_SERVER['DOCUMENT_ROOT'] . "/inc/" . $_SERVER['REQUEST_URI']);
	}
	else{
		include($_SERVER['DOCUMENT_ROOT'] . "/SceneUI" . $_SERVER['REQUEST_URI']);
	}
*/
?>