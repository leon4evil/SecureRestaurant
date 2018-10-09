<?php   
	//this form will be filled by user to add new dish
	
	session_start();
	session_regenerate_id();
	isset( $_REQUEST['s'] ) ? $s =strip_tags($_REQUEST['s']) : $s = "";		
	isset( $_REQUEST['postdishesId'] ) ? $postdishesId=strip_tags($_REQUEST['postdishesId']) : $postdishesId="";
	isset( $_REQUEST['postdishname'] ) ? $postdishname=strip_tags($_REQUEST['postdishname']) : $postdishname="";	
	isset( $_REQUEST['postpicture'] ) ? $postpicture=strip_tags($_REQUEST['postpicture']) : $postpicture="";	
	isset( $_REQUEST['postdescription'] ) ? $postdescription=strip_tags($_REQUEST['postdescription']) : $postdescription="";	
	
	
	include_once('/var/www/brightoncorestaurants.com/public_html/FLR-lib.php');
	include_once('/var/www/brightoncorestaurants.com/public_html/header.php');
	icheck($s);
	icheck($postdishesId);	
	checkSession();	

echo"<html>
	<head>
		<title>Add Dish </title>
	</head>
	<body> ";

if(isset($_SESSION['authenticated'])){
	echo"<h1> Adding dish</h1>";
	echo"<h3>";
			//remember <input text= > does not like spaces 
	echo"<form action=\"/add.php\" method=\"post\">
			<input type=\"hidden\" name=\"f\" value=\"1\" readonly>
		         <p>Dish Name:
		     	<textarea  name=\"postdishname\" /> </textarea> </p>
		     	<p>Dish Picture:
		     	<input type=\"text\" name=\"postpicture\" ></p>
		     	<p>Description:
		     	<textarea name=\"postdescription\" cols=\"20\" rows=\"4\"></textarea></p>
		     	<input type=\"submit\" value=\"Submit\"/>
	      </form>";
	echo"</h3>";
	}
else{
	echo"<h1>You're not authorized to view this page.</h1>";
	}
	
	echo"	</body>
	</html>";




?>
