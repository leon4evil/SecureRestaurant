<?php
        session_start();	
	session_regenerate_id();
	isset( $_REQUEST['s'] ) ? $s =strip_tags($_REQUEST['s']) : $s = "";	
	isset( $_REQUEST['name'] ) ? $name =strip_tags($_REQUEST['name']) : $name = "";
	isset( $_REQUEST['rid'])? $rid=strip_tags($_REQUEST['rid']) : $rid="";
	isset( $_REQUEST['posttype'])? $posttype=strip_tags($_REQUEST['postype']) : $posttype= "";

	include_once('/var/www/brightoncorestaurants.com/public_html/FLR-lib.php');
	include_once('/var/www/brightoncorestaurants.com/public_html/header.php');
	connect($db);
	icheck($s);
	icheck($rid);
	checkSession();	
	echo"<html>
		<head>
			<title> Brighton CO Restaurants  </title>
		</head>
		<body>";
		
	echo $posttype;	
	if(!isset( $_SESSION['authenticated'])) { //if user is not loged in
		echo"
		<a href=login.php?rid=$rid&s=$s&name=",urlencode($name)," > Login to make changes  |</a>"; //urlencode allows us to have spaces in post variable 
		}
		else{
			echo "<p><a href=editadd.php?s=80> Add Item |</a>";                       
                        if($_SESSION['restaurantId']==1  ){//if user is the administrator
				echo "<a href=index.php?s=5> Add Manager |</a>";
				echo "<a href=index.php?s=6> Add Restaurant |</a>";
			}
			
                        echo"<a href=index.php?s=4> Logout |</a>";
			echo"</p><hr/>";

		}
		
		echo"<h1> Brighton CO Restaurants </h1>";
 		

	switch($s){
		case 0: //show restaurants
		 default:
					
			//filter restaurants by type	
			if ($stmt = mysqli_prepare($db, "select type from Restaurant where type!='admin'")){
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$type);
       			 echo" <form action=\"index.php\" method=\"get\">
	                		<select name = \"posttype\">
                                	<option value=\"\"> \"filter by restaurant type...\"</option>";
                        while(mysqli_stmt_fetch($stmt)){
				$type=htmlspecialchars($type);
				$posttype=urlencode($type);
                        	  echo "<option value=$posttype>$type</option>";
                        	}
								
                		 echo " </select>
                        	        <input type=\"submit\" value=\"Submit\">
                       	      </form >  ";	
			}	
				

				
		if(!isset( $_REQUEST['posttype']) || $_REQUEST['posttype']=="All" || $_REQUEST['posttype']==""){//if user is not using filtering
                	$query="SELECT restaurantId,name FROM Restaurant WHERE restaurantId!=1";
		}
                else{//user is not using filtering	
			$posttype=$_REQUEST['posttype'];
			$posttype=mysqli_real_escape_string($db, $posttype);
                	$query="SELECT restaurantId,name FROM Restaurant WHERE type=\"".urldecode($posttype)."\"";
		}
		$result=mysqli_query($db, $query);
                while($row=mysqli_fetch_row($result)) {
			echo "<tr> <td> $row[0] </td><td> <a
                	href=index.php?","s=1&rid=$row[0]&name=",urlencode($row[1]),">"; //urlencode allows us to have spaces in post variable 
			echo "$row[1] </a></td></tr> \n";	
			}

		break;

		case 1://show restaurant Dishes
			
			if($_SESSION['authenticated'] && ($_SESSION['restaurantId'] != $rid)&&($_SESSION['restaurantId']!=1) ){
				echo("<p><font color=\"red\"> You cannot modify this restaurant</font></p>");//show if not mnager for current restaurant	
			}
			
			echo "<b> Dishes offered by $name </b> \n ";	
			echo "<table>";
			$rid=mysqli_real_escape_string($db, $rid);
			if ($stmt = mysqli_prepare($db, "SELECT dishesId,name,picture,description FROM Dishes WHERE restaurantId = ?")){
				mysqli_stmt_bind_param($stmt, 's', $rid);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt,$did, $dish,$picture,$description);
       		 		while(mysqli_stmt_fetch($stmt)) {
       		         		$did=htmlspecialchars($did);
					$dish=htmlspecialchars($dish);
					$picture=htmlspecialchars($picture);
					$decription= ($description);
					echo"<form action=\"editdish.php\" id=\"dishform\"method=\"post\">";
					echo"<tr><td><img src=$picture><br/>";
					echo "<a href=index.php?did=$did&s=2> $dish </a>\n";
					
		     			echo "<input type=\"hidden\" name=\"postdishesId\" value=$did readonly>";
					echo "<input type=\"hidden\" name=\"postpicture\" value=$picture />";	
					echo "<input type=\"hidden\" name=\"postdishname\" value=",urlencode($dish)," />";	
					echo "<textarea name=\"postdescription\" cols=\"40\" rows=\"4\" value=$description readonly>$description</textarea><tr><td>";
					
					if((isset( $_SESSION['authenticated']) && ($_SESSION['restaurantId'] == $rid)) || ($_SESSION['restaurantId']==1)) {//only show change button if manager is oged in.
						echo "<input type=\"submit\" value=\"change\"/>";
					}
					echo"</form>";
	    			}    
				mysqli_stmt_close($stmt);
			}
		break;
	
		case 4: //logout
		
			session_unset();
        		session_destroy();	
			if($rid){	
                        	header("Location: index.php?s=1&rid=$rid&name=$name");
			}
			else{
				header("Location: index.php?s=0&rid=$rid&name=$name");	
			}
		break;
		
		case 5://fill out form to add a manager

			if($_SESSION['authenticated'] && ($_SESSION['restaurantId']==1)){//make sure is Admin
					
					if($rid==-1){
					echo "
					<style type=\"text/css\">
					p.warning{
					color: #FF0000;
					}	
					</style>";
					echo "<p class=\"warning\"> Please make sure you assign  a Restaurant</p>";
					
					}
			
				if ($stmt = mysqli_prepare($db, "SELECT restaurantId,name FROM Restaurant WHERE restaurantId!=1")){
					mysqli_stmt_execute($stmt);
					mysqli_stmt_bind_result($stmt,$rid,$name);
					
					echo"<p></p>";	
       			 		echo" <form action=\"add.php\" method=\"post\">
	                				<select name = \"rid\">
                                			<option value=\"\"> \"select managers restaurant...\"</option>";

                        				while(mysqli_stmt_fetch($stmt)){
                                			//	$rid=htmlspecialchars($rid);
								$name=htmlspecialchars($name);
                                				echo "<option value=$rid>$name</option>";
                        				}
                				echo " </select>
							<p> New Manager Name:	
                                				<input type=\"text\" name=\"managername\" />
							</p>
							<p> New Manager Password:
								<input type=\"password\" name=\"password\" />
							</p>
                                			<input type=\"text\" name=\"f\" value=10 hidden/>
                                			<input type=\"submit\" value=\"Submit\">
                       					</form >  ";	

				}	
			}	
		
		break;				
		
		case 6:
			
       			echo" <form action=\"add.php\" method=\"post\">";
			echo "
                                                        <p> New Restaurant  Name:
                                                                <input type=\"text\" name=\"restaurantname\" />
                                                        </p>
                                                        <p> New Restaurant  type:
                                                                <input type=\"text\" name=\"restauranttype\" />
                                                        </p>
                                                        <input type=\"text\" name=\"f\" value=11 hidden/>
                                                        <input type=\"submit\" value=\"Submit\">
                                                        </form >  ";	

		break;
		}
		echo "</table>";
	echo   "</body>
	     </html>";

?>
