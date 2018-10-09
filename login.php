<?php
	 session_start();
        session_unset();
        session_destroy();
        isset( $_REQUEST['givenuser'] ) ? $givenuser=strip_tags($_REQUEST['givenuser']) : $givenuser= "";
        isset( $_REQUEST['givenpass'] ) ? $givenpass=strip_tags($_REQUEST['givenpass']) : $givenpass= "";
        //isset( $_REQUEST['attempts'] ) ? $attempts=strip_tags($_REQUEST['attempts']) : $attempts= "";	
	isset( $_REQUEST['name'] ) ? $name =strip_tags($_REQUEST['name']) : $name = "";
	isset( $_REQUEST['rid'])? $rid=strip_tags($_REQUEST['rid']) : $rid="";
	isset( $_REQUEST['s'])? $s=strip_tags($_REQUEST['s']) : $s="";
	isset( $_REQUEST['failedlogin'])? $failedlogin=strip_tags($_REQUEST['failedlogin']) : $failedlogin="";	
	
	include_once('/var/www/brightoncorestaurants.com/public_html/FLR-lib.php');
	include_once('/var/www/brightoncorestaurants.com/public_html/header.php');
	icheck($s);
	icheck($rid);	
	checkSession();	
	connect($db);
	echo"   <html>
        <head> 
                <title> Brighton CO Restaurants Login </title> 
                 <body>";
		if($failedlogin=="yes"){

			echo"<p>Failed Login</p>";
			$query="INSERT INTO FailedLogins SET ip=?,time=?";
			$remoteaddress =mysqli_real_escape_string($db,$_SERVER['REMOTE_ADDR']);
			$matime = date("Y-m-d h:i:s"); 
			if($stmt=mysqli_prepare($db,$query)){
				mysqli_stmt_bind_param($stmt,"ss",$remoteaddress,$matime);
				mysqli_stmt_execute($stmt);
			}
			mysqli_stmt_close($stmt);
		}
		$remoteaddress = $_SERVER['REMOTE_ADDR']; //check if ip is not black listed
		$query="SELECT ip FROM FailedLogins WHERE ip=?";
		if($stmt=mysqli_prepare($db,$query)){	
			mysqli_stmt_bind_param($stmt,'s',$remoteaddress);
                	mysqli_stmt_execute($stmt);
			//printf("error %s \n", mysqli_connect_error());
                	mysqli_stmt_bind_result($stmt, $clientip);
			mysqli_stmt_store_result($stmt);
			
			while(mysqli_stmt_fetch($stmt)){
                        	$clientip=htmlspecialchars($clientip); 
                        }
		}
		if(mysqli_stmt_num_rows($stmt)>4){ //if user has failed more than 5 times 
			echo"<p> Your IP has been blocked after 5 failed login attempts. </p>";
			//error_log("ASSSSSSSSSSSSSSSSSSS");
			echo "<p>";
			echo date("Y-m-d h:i:s");
			echo "</p>";
			echo "<p>";
			echo $remoteaddress;
			echo "</p>";
			//echo mysqli_stmt_num_rows($stmt);
			mysqli_stmt_close($stmt);
		}
		else{
		
			mysqli_stmt_close($stmt);
		//printf("error %s \n", $stmt->error);
		//echo $clientip;
		//echo $remoteaddress;
		error_log("ASSSSSSSSSSSSSSSSSSS");
                echo "<p><b>Enter Credentials.</b></p>
                        <hr/>";
                echo"<form action=\"/processlogin.php\" method=\"post\">
                     <p>Username:
                     <input type=\"text\" name=\"postUsername\"/></p>
                     <p>Password:
                     <input type=\"password\" name=\"postPassword\"</p>
		     
		     <input type=\"hidden\" name=\"s\" value=$s>
		     <input type=\"hidden\" name=\"rid\" value=$rid>";

		echo"<input type=\"hidden\" name=\"name\" value=",urlencode($name),">";
		echo"<input type=\"submit\" value=\"Login\"/>
                     </form>";

		}


	echo"
        	      </body>                         
        	</head> 
	</html> ";

?>
