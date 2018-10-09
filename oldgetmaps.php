<?php
//This File shows two ways of showing an image provided by the google static maps API
echo "<html> 
	<head>
		<title>Show me the maps</title>
		<style type=\"text/css\">	
		div{
		 float:left;	
		 width:100%;}
		
		img.medium{
			width: 250px;
			height: 250px;}
		</style>
	</head> 
		<body>";
 //phpinfo(); //prints info about your php varsion
	
	$requestURL = 'https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318&markers=color:red%7Clabel:C%7C40.718217,-73.998284&key=AIzaSyC4XdNQeAcl1DNZCyV3qQ7lXHrGTCR1yuQ'; //This is the url that gets you the map image in a png

	// First Way this way gets the image, saves it to the server. once it is on the server we can display it using the <img> taag
	//header('Content-Type: image/png');
	$data = imagecreatefrompng($requestURL); //first create a variable with the image data in it
	$input = $requestURL; //here we are just preping to save the file
	$output = 'thismofo2.png';
	//echo '<p>'.getcwd().'</p>'; // gets the server folder where youre operating
	echo'<div>';
	file_put_contents($output, file_get_contents($input)); //remember we had to use change SELINUX context on the file to make this work 
	echo "<p><img src=\"thismofo2.png\" class=\"align-left medium\"alt=\"SUCK IT!\" width=\"600\" height=\"300\"/>
		 Using the file saving scheme</p>";
	echo "<br/>";
	echo "<hr/>";
	
	//Second Smarter way: This way gets the image then encodes it as URI data then we can just save the variable and  put it in the <img> tag 
	$imageData = base64_encode(file_get_contents($requestURL));	
	//$src = 'data:'.mime_content_type($data).';base64,'.$imageData;//this gets the type of image dynamically but it does not work with the 
									//map image provided by the google API however we know it is a png as per 
									//google documentation
	$src = 'data:image/png;base64,'.$imageData;
	echo "<p><img src=$src class=\"align-right medium\" alt=\"SUCK IT!\" width=\"600\" height=\"300\"/>Using the uri scheme</p>";

	//echo '<p>'.getcwd().'</p>';
	echo'</div>';
	echo"		</body>
		</html>";

?>
