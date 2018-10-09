<?php
	session_start();	
	session_regenerate_id();
	//this page edit a dish or add a dish in the database
	
	isset( $_REQUEST['f'] ) ? $f =strip_tags($_REQUEST['f']) : $f = "";
	isset( $_REQUEST['rid'] ) ? $rid=strip_tags($_REQUEST['rid']) : $rid="";
	isset( $_REQUEST['postdishesId'] ) ? $postdishesId=strip_tags($_REQUEST['postdishesId']) : $postdishesId="";
	isset( $_REQUEST['postdishname'] ) ? $postdishname=strip_tags($_REQUEST['postdishname']) : $postdishname="";	
	isset( $_REQUEST['postpicture'] ) ? $postpicture=strip_tags($_REQUEST['postpicture']) : $postpicture="";	
	isset( $_REQUEST['postdescription'] ) ? $postdescription=strip_tags($_REQUEST['postdescription']) : $postdescription="";	
	
	isset( $_REQUEST['managername'] ) ? $managername=strip_tags($_REQUEST['managername']) : $managername="";
	isset( $_REQUEST['password'] ) ? $password=strip_tags($_REQUEST['password']) : $password="" ; 	
	
	isset( $_REQUEST['restaurantname'] ) ? $restaurantname=strip_tags($_REQUEST['restaurantname']) : $restaurantname="";
	isset( $_REQUEST['restauranttype'] ) ? $restauranttype=strip_tags($_REQUEST['restauranttype']) : $restauranttype="";


	include_once('/var/www/brightoncorestaurants.com/public_html/FLR-lib.php');
	include_once('/var/www/brightoncorestaurants.com/public_html/header.php');
	connect($db);
	icheck($s);
	icheck($f);
	icheck($postdishesId);
	icheck($rid);
	checkSession();	
	//header
        echo"<html> 
        <head>
                <title>Dish Edit </title>
        </head>
        <body> ";
	
	//check if  authenticated
	//if(!isset( $_SESSION['authenticated'])) {
          //      authenticate($db,$postUsername,$postPassword);
	//}
        //else{
  	  //  $f=93;
        //}
         
	switch ($f){
	
		case 0: //editing Dish
		default:
		       $postdishname=mysqli_real_escape_string($db, $postdishname);
                       $postpicture=mysqli_real_escape_string($db,$postpicture);
		       $postdescription=mysqli_real_escape_string($db,$postdescription);
		       $postdishesId=mysqli_real_escape_string($db,$postdishesId);
	               echo $postdishesId;	
			if($stmt=mysqli_prepare($db, "SELECT restaurantId FROM Dishes WHERE dishesId=?")){//this if statement
													 //makes sure admin is editing 
													//a dish for his restaurant
				mysqli_stmt_bind_param($stmt,"s", $postdishesId);
         	                mysqli_stmt_execute($stmt);	
				mysqli_stmt_bind_result($stmt,$ResId);			
       		 		while(mysqli_stmt_fetch($stmt)) {
       		         		$ResId=htmlspecialchars($ResId);
				}
				mysqli_stmt_close($stmt);
			}
			if($ResId==$_SESSION['restaurantId'] || $_SESSION['restaurantId']==1){ //if true proceed with edit
				echo "\"",$postdescription,"\"";
		       		if($stmt=mysqli_prepare($db,"UPDATE Dishes SET name=?, picture=?, description=? WHERE dishesId=?")){	
                	        	mysqli_stmt_bind_param($stmt,"ssss",$postdishname,$postpicture,$postdescription,$postdishesId);
         	                	mysqli_stmt_execute($stmt);
                        		mysqli_stmt_close($stmt);
                        		echo "AWESOME";
					echo "<h1>Dish Successfully edited</h1>";	
					echo "<a href=index.php?s=0> Back </a>\n";
				}			
				else{
                        		echo "Error with query";
                		}
                	}
			break;

		case 1://adding dish
				
		if($_SESSION['authenticated'] || ($_SESSION['restaurantId']==1)){//make sure is manager or Admin
		       $postdishname=mysqli_real_escape_string($db, $postdishname);
                       $postpicture=mysqli_real_escape_string($db,$postpicture);
		       $postdescription=mysqli_real_escape_string($db,$postdescription);
		       $ResId =$_SESSION['restaurantId']; 

		       if($stmt=mysqli_prepare($db,"INSERT INTO Dishes SET name=?, picture=?, description=?, restaurantId=?")){	
                	        if($_SESSION['restaurantId']!=1){//if user is manager but not admin
					mysqli_stmt_bind_param($stmt,"ssss",$postdishname,$postpicture,$postdescription,$ResId);
				}
				else{//user is admin so 
					mysqli_stmt_bind_param($stmt,"ssss",$postdishname,$postpicture,$postdescription,$postrestaurantId);		
				}
         	                mysqli_stmt_execute($stmt);
                        	mysqli_stmt_close($stmt);
                        	echo "AWESOME";
				echo "<h1>Dish Successfully Added</h1>";
				echo "<a href=index.php?s=0> Back </a>\n";
			}
		}
		
		break;
		
		case 10: //adding manager
			
		if($_SESSION['authenticated'] && ($_SESSION['restaurantId']==1)){//make sure is Admin
			
						if(!$rid==""){
						
						
			$managername=mysqli_real_escape_string($db, $managername);
			$password=mysqli_real_escape_string($db, $password);
			$rid=mysqli_real_escape_string($db, $rid);
			$saltend="\n";
			$salt=hash('sha256',"salt".$saltend);
                	$salt=htmlspecialchars($salt);
			$password=hash('sha256',$password.$salt);
			
			if($stmt=mysqli_prepare($db,"INSERT INTO Managers SET name=?, password=?, restaurantId=? ,salt=?")){
				mysqli_stmt_bind_param($stmt,"ssis",$managername,$password,$rid,$salt);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
				echo "<p>Successfully Added Manager</p>";
				echo "<p>YAY!</p>";
				echo "<a href=index.php?s=0> Back </a>\n";
			}	
			}else{
				$rid=-1;
				header("Location: index.php?rid=$rid&s=5");
			}		
		}
		break;
		case 11: //adding restaurant
			
			if($_SESSION['authenticated'] && ($_SESSION['restaurantId']==1)){//make sure is Admin
			
				$restaurantname=mysqli_real_escape_string($db,$restaurantname);
				$restauranttype=mysqli_real_escape_string($db,$restauranttype);
							
				if($stmt=mysqli_prepare($db,"INSERT INTO Restaurant SET name=?, type=?")){
					mysqli_stmt_bind_param($stmt,"ss",$restaurantname,$restauranttype);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);	
					echo "<p>Successfully Added Restaurant</p>";
					echo "<p>YAY!</p>";
					echo "<a href=index.php?s=0> Back </a>\n";
				}
			}
			else{
				echo"<p>You're not an Admin!</p>";
			}
		break;

		//case 93:
		//	header('FortLuptonRestaurant/login.php');
		//
		//break;

		}
?>
