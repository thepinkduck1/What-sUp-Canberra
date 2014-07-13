<?php
	$homepage = file_get_contents('http://api.visitcanberra.com.au/events/datasets/events.json');
	$myfile = fopen("list.json", "w") or die("Unable to open file!");
	fwrite($myfile, $homepage) or die("Unable to edit file");
	fclose($myfile);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css' />
<link type="image/png" rel="icon" href="../favicon.png" />
<title>
What'sUp Canberra - Food and Wine
</title>
<link type="text/css" rel="stylesheet" href="list.css">
</head>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<body>
<div class="header">What'sUp Canberra - Food and Wine<a href="../"><img src="../images/home-50.png" alt=Home /></a></div>
<div id="sidebar"><img src="../images/loading-icon.gif" id="loader" alt="Loading image" /></div>
<div id="main"><p class="initp">Press any button to see results for that item.<br />&#8626;</p></div>
<script type="text/javascript">
function makeUL(json) {
    var list = document.createElement('ul');
	index = 0;
    for (var i = 0; i < json.length; i++) {
    	if (typeof json[i].categories[0] != "undefined") {
	    	if (json[i].categories[0].typeid == "FOODWINE" && json[i].name != null) {
	    		index += 1
		        var item = document.createElement('li');
		        if (index % 2 == 0) {
		        	item.style.background = "white";	
		        }
		        else {
		        	item.style.background = "#d9d9d9";	
		        }
				if (json[i].free_entry_flag == "1") {
					item.setAttribute("class", "green");
				}
				item.setAttribute("data-index", i);
		        item.appendChild(document.createTextNode(json[i].name));
		        list.appendChild(item);
	    	}
    	}
    }

    return list;
}

$("#sidebar").on("click", "li", function(event){
	var control = $(this).attr("data-index");
	$.getJSON("list.json", function(data) {
		document.getElementById("main").innerHTML = "<h1>" + data[control].name + "</h1>";
		if (typeof data[control].image != "undefined") {
    		document.getElementById("main").innerHTML += "<img src='" + data[control].image + "' width='400px' style='float: right; margin-left: 10px;' />";
    	}
		if (typeof data[control].start_date != "undefined") {
    		document.getElementById("main").innerHTML += "<h3>Begins " + data[control].start_date + "</h3>";
    	}
    	if (typeof data[control].end_date != "undefined") {
    		document.getElementById("main").innerHTML += "<h3>Ends " + data[control].end_date + "</h3>";
    	}
    	if (typeof data[control].description != "undefined") {
    		document.getElementById("main").innerHTML += "<h2>Description</h2>" + data[control].description;
    	}
    	if (typeof data[control].address != "undefined") {
    		document.getElementById("main").innerHTML += "<h2>Location</h2><p>" + data[control].address + "<form action='../map/map.php' method='post'><input type='hidden' name='address' id='address' value='" + data[control].address + "' /><input type='submit' id='button' value='Get directions' class='button'/></form>";
    		$("#address").val(data[control].address);
    	}
    	if (data[control].entryCosts.length > 0) {
    		document.getElementById("main").innerHTML += "<h2>Entry Fees</h2>";
	    	for (var x = 0; x < data[control].entryCosts.length; x++) {
	    		document.getElementById("main").innerHTML += "<p>" + data[control].entryCosts[x].description + ": $" + Math.round(data[control].entryCosts[x].value * 100) / 100 +"</p>";	
	    	}
    	}
	});
});

$.getJSON("list.json", function(json) {
	document.getElementById('sidebar').appendChild(makeUL(json));
	document.getElementById('loader').remove();
});

	</script>
</body>
</html>