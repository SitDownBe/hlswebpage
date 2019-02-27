<?php

// Script for calling FFmpeg to convert a video.

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];
$startSegment = $_REQUEST["s"];
//$inputVideo = "aerial.mov";
//$videoName = "aerial";
//$startSegment = "0";

$segments = "m3u8_files/" . $videoName . "%01d.ts";
$playlist = "m3u8_files/NUL/" . $videoName . ".m3u8";
// Segment length must be 2 seconds.
$startTime = gmdate("H:i:s", $startSegment * 2);
// Creating a temporary endTime variable, for testing.
//$endTimes = gmdate("H:i:s", $startSegment * 2 + 4);

// Hold the script until a playlist exists, which is created by another script.
do {
	if (file_exists("m3u8_files/" . $videoName . ".m3u8"))
	{
		echo "Ready\n";
		break;
	}
	sleep(1);
} while (true);

//$ffmpeg = "ffmpeg -n -i " . $inputVideo . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -crf 21 -maxrate 600k -bufsize 600k -b:a 64k -ar 44100 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -hls_segment_filename " . $segments . " " . $playlist . " > /dev/null 2>&1 &";

// FFmpeg Transcoding settings.
$ffmpeg = "ffmpeg -i " . $inputVideo . " -ss " . $startTime . " -c:v libx264 -preset placebo -s 640x480 -r 30 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -start_number " . $startSegment . " -hls_segment_filename " . $segments . " " . $playlist . " > /dev/null 2>&1 &";
// -to " . $endTimes . "
// -hls_flags temp_file <--- Seems to be bugged.
// -hls_flags single_file
// -hls_segment_filename " . $segments . " 

// Call the system to start converting.
if (!file_exists("m3u8_files/" . $videoName . $startSegment . ".ts"))	
{
	echo "Starting transcoding\n";
	echo $ffmpeg;
	shell_exec($ffmpeg);
}
else
{
	echo "Converted file already exists";
}

echo "\n";
?>
