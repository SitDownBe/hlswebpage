<?php

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];

$ffmpeg = "ffmpeg -i " . $inputVideo . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -crf 21 -maxrate 600k -bufsize 600k -b:a 64k -ar 44100 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -hls_flags single_file -strict -2 /var/www/html/hlswebpage/videos/m3u8_files/" . $videoName . ".m3u8";


echo "Starting transcoding\n\n";

echo $ffmpeg;
shell_exec($ffmpeg);

echo "\n\nTranscoding complete\n";

?>