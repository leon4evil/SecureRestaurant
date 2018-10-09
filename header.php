<?php	

isset( $_REQUEST['s'] ) ? $s =strip_tags($_REQUEST['s']) : $s = "";

function icheck($i) { //Check for numeric
	if ($i != null) {
		if(!is_numeric($i)) {
			print "<b> ERROR: </b>
			Invalid Syntax. ";
			exit;
			}
		}
	}

?>
