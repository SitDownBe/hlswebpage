
// Global variables.
var rootPath = "http://localhost/hls.player/";
var videoPath = rootPath + "videos/";
var targetPath = videoPath + "m3u8_files/";
var url = "";


// Check if the video can be played directly or has to be transcoded first.
function playVideo() 
{
	var v = document.getElementById("videoDropdown");
	var inputVideo = v.options[v.selectedIndex].value;
	var videoName = inputVideo.split(".");
	var filetype = videoName[videoName.length-1];
	videoName = videoName[0];

	// Need HLS to be supported by the browser.
	if (Hls.isSupported()) 
	{
	    if (filetype === "m3u8")
	    {
	    	console.log("Starting playback of: " + videoName + ".m3u8");
	    	url = targetPath + inputVideo;
			startPlayback();
		}
		else if (fileExists(videoName, inputVideo))
		{
	    	console.log("Starting playback of: " + videoName + ".m3u8");
			startPlayback();
		}
		else
		{	
			// Need to convert the file to HLS format.
			var xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() 
				{
		            if (this.readyState == 4 && this.status == 200)
		            {
		                console.log(this.responseText);
		            	// Start playback when the transcoding is complete.
		            	url = targetPath + videoName + ".m3u8";
		            	startPlayback();
	        		}
				};

			console.log("Requesting transcoding...");
			// Send request to server to convert the file.
			xmlhttp.open("GET", rootPath + "videos/encoder.php?i=" + inputVideo + "&n=" + videoName, true);
	    	xmlhttp.send();
		}
	}
}

// Start playback of video, requires 'controls' in the HTML tag to interact with.
function startPlayback() 
{
	var video = document.getElementById('video');

	var hls = new Hls();

	// Bind video element to HLS element.
    hls.attachMedia(video);

    // MEDIA_ATTACHED event is fired by hls object once MediaSource is ready
    hls.on(Hls.Events.MEDIA_ATTACHED, function()
    {
    	hls.loadSource(url);	
    	hls.on(Hls.Events.MANIFEST_PARSED, function(){});
    });
}

// Check if the input file has already been transcoded and stored.
function fileExists(videoName, inputVideo)
{
	console.log("Unsupported format of: " + inputVideo);
	var xmlhttp = new XMLHttpRequest();

	console.log("Checking if transcoded video exists...");
	xmlhttp.open("GET", targetPath + videoName + ".m3u8", false);
	xmlhttp.send();
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
	{
		console.log("Transcoded video found in storage");
    	url = targetPath + videoName + ".m3u8";
		return true;
	}
	else
	{
		return false;
	}
}
