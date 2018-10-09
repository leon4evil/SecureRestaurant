<?php
	function connect(&$db){
		$mycnf="/etc/FLR-mysql.conf";
		if (!file_exists($mycnf)) {
			echo "ERROR: DB Config file not found:
			$mycnf";
			exit; 
		}

		$mysql_ini_array=parse_ini_file($mycnf);
		$db_host=$mysql_ini_array["host"];
		$db_user=$mysql_ini_array["user"];
		$db_pass=$mysql_ini_array["pass"];
		$db_port=$mysql_ini_array["port"];
		$db_name=$mysql_ini_array["dbName"];
	
		//$db=mysqli_init();
		$db_sslkey='/etc/mysql-ssl/server-key.pem';
		$db_sslcert='/etc/mysql-ssl/server-cert.pem';
		//mysqli_ssl_set($db,$db_sslkey,$db_sslcert,NULL,NULL,NULL); //breaks app when using php 7 and mysqli_real_connect.
									     //we're not encripting connection to db currently but might change later 
		//mysqli_real_connect($db,$db_host,$db_user,$db_pass,$db_name,$db_port);
		$db=mysqli_connect($db_host,$db_user,$db_pass,$db_name,$db_port); //works well with php 7 but take out init
		if (mysqli_connect_errno()) {
			print "Error connecting to DB:" .  mysqli_connect_error();
			exit;
		}
	} 
	
	function checkSession(){//do security checks for sessions
        if (isset($_SESSION['HTTP_USER_AGENT'])){//check MD5 of the user agent
                        if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])){
                                logout();
                        }
        }
        else{
                logout();
        }

        if(isset($_SESSION['ip'])){//make sure session ip is the same as clients ip
                if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']){
                        logout();
                }
        }
        else{
                logout();
        }

        if(isset($_SESSION['created'])){//time out session after a while
                if(time() - $_SESSION['created']>1800){
                        logout();
                }
        }
        else{
                logout();
        }
	}
	
function logout(){
        session_unset();
        session_destroy();
}

?>
