<?php   //this form prefills the dish being edited
        //on submit it will send the info to change the dish entry in the database
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
		<title>Dish Edit </title>
	</head>
	<body> ";

if(isset($_SESSION['authenticated'])){
	echo"<h1>Editing ",urldecode($postdishname)," dish</h1>";
	echo"<h3>";
			//remember <input text= > does not like spaces 
	echo"<form action=\"/add.php\" method=\"post\">
			<input type=\"hidden\" name=\"postdishesId\" value=$postdishesId readonly>
			<input type=\"hidden\" name=\"f\" value=\"0\" readonly>
		         <p>Dish Name:
		     	<textarea  name=\"postdishname\" />", urldecode($postdishname)," </textarea> </p>
		     	<p>Dish Picture:
		     	<input type=\"text\" name=\"postpicture\" value=$postpicture ></p>
		     	<p>Description:
		     	<textarea name=\"postdescription\" cols=\"20\" rows=\"4\">$postdescription</textarea></p>
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
