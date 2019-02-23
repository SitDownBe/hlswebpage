<?php
// Script for creating a mock HLS playlist to inform the videoplayer how long
// the full video will eventually become when the conversion is done.

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];
//$inputVideo = "aerial.mov";
//$videoName = "aerial";

// Get the original video length.
$videoLength = shell_exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 " . $inputVideo);

// Create the mock file.
$playlist = fopen("m3u8_files/" . $videoName . ".m3u8", "w") or die("Unable to write file");

// Insert headers into it.
$header = "#EXTM3U\n#EXT-X-VERSION:3\n#EXT-X-TARGETDURATION:2\n#EXT-X-MEDIA-SEQUENCE:0\n";
fwrite($playlist, $header);

// Insert segment information.
$i = 0;
while ($videoLength >= 0) 
{
	if ($videoLength >= 2)
	{
		$body = "#EXTINF:2.000000,\n" . $videoName . $i . ".ts\n";
		fwrite($playlist, $body);
	}
	else
	{
		$body = "#EXTINF:" . $videoLength . ",\n" . $videoName . $i . ".ts\n";
		fwrite($playlist, $body);
	}
	$videoLength -= 2;
	$i += 1;
}

// Insert end of file.
$end = "#EXT-X-ENDLIST";
fwrite($playlist, $end);

?>