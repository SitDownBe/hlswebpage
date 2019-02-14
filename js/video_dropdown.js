
var select = document.getElementById("videoDropdown");

var xmlhttp = new XMLHttpRequest();

xmlhttp.onreadystatechange = function() 
	{
        if (this.readyState == 4 && this.status == 200)
        {
        	// Get all MOV files in directory "/videos".
        	var videos = this.responseText.split(";");
        	// Last element is an empty string, we pop it.
        	videos.pop();
        	// Populate dropdown menu with the files.
        	videos.forEach(function(item)
	        	{
	            var el = document.createElement("option");
	            el.textContent = item;
	            el.value = item;
	            select.appendChild(el);
	        	});
		}
	};

xmlhttp.open("GET", "http://localhost/hls.player/videos/video_index.php", true);
xmlhttp.send();