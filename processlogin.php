<?php 
	session_start();
	session_regenerate_id();

	isset( $_REQUEST['postUsername'] ) ? $postUsername=strip_tags($_REQUEST['postUsername']) : $postUsername="";
	isset( $_REQUEST['postPassword'] ) ? $postPassword=strip_tags($_REQUEST['postPassword']) : $postPassword="";
	
	isset( $_REQUEST['name'] ) ? $name =strip_tags($_REQUEST['name']) : $name = "";
	isset( $_REQUEST['rid'])? $rid=strip_tags($_REQUEST['rid']) : $rid="";
	isset( $_REQUEST['s'])? $s=strip_tags($_REQUEST['s']) : $s="";
	
	include_once('/var/www/brightoncorestaurants.com/public_html/FLR-lib.php');
	include_once('/var/www/brightoncorestaurants.com/public_html/header.php');
	icheck($s);
	icheck($rid);	
	//checkSession();	
	
	connect($db);

	
	//echo"<html> 
	//	<head>
	//		<title> process login </title>
	//	<head>
	//	<body>";
	//	
	//echo $s , $rid , $name;
	//echo"	</body>

	  //  </html>";	
	//sleep(5);
	authenticate($db,$postUsername,$postPassword,$s,$rid,$name);
	

function authenticate($db,$givenuser,$givenpass,$s,$rid,$name){ //authenticate user
        $query="SELECT password,salt,restaurantId FROM Managers WHERE name=?";
	$givenuser = mysqli_real_escape_string($db,$givenuser);
        $givenpass = mysqli_real_escape_string($db,$givenpass);
	$s= mysqli_real_escape_string($db,$s);
	$rid = mysqli_real_escape_string($db,$rid);
	$name = mysqli_real_escape_string($db,$name);
	
	if($stmt=mysqli_prepare($db,$query)){
                mysqli_stmt_bind_param($stmt,'s',$givenuser);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $password,$salt,$restaurantId);

                while(mysqli_stmt_fetch($stmt)){
                        $password=htmlspecialchars($password);
                        $salt=htmlspecialchars($salt);
			$restaurantId=htmlspecialchars($restaurantId);
                        }
                mysqli_stmt_close($stmt);
                $epass=hash('sha256',$givenpass.$salt);
                if($epass==$password){
			session_regenerate_id();
                        $_SESSION['name']=htmlspecialchars($givenuser);
                        $_SESSION['restaurantId']=htmlspecialchars($restaurantId);
                        $_SESSION['authenticated']="yes";
			$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
			$_SESSION['created']=time();
			header("Location: /index.php?s=$s&rid=$rid&name=$name");
                }
                else{
                        echo "<p>Failed Login</p>";
			$combo = $givenpass.$salt;
			$failedlogin = "yes";
                        header("Location: /login.php?s=$s&rid=$rid&name=$name&failedlogin=$failedlogin");
                }
        }
}	
?>
