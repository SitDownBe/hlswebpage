<?php

$files = scandir("/var/www/html/hlswebpage/videos/");

// Find video files in current directory.
foreach ($files as $value)
{
	$type = explode(".", $value);
	if ($type[count($type)-1] === "mov" || $type[count($type)-1] === "mp4")
	{
		echo $value;
		echo ";";
	}	
}

?>