<?php

$str = "test@gmail.com";

echo "<pre>";
print_r(split('@',$str)[0]);
echo "</pre>";

$i = 0;
$result = "";

while($str[$i] !== '@'){
	$result.=$str[$i];
	$i++;
}

echo $result;


?>