<?php

$files = scandir("/var/www/html/hlswebpage/videos/");

foreach ($files as $value)
{
	$type = explode(".", $value);
	if ($type[count($type)-1] === "mov")
	{
		echo $value;
		echo ";";
	}	
}

?>