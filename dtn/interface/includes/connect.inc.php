<?php
function connect(){




				
		$hote_c = "localhost";
		$login_c = "htfff";
		$pwd_c = "ht!fff_2k15";
		$db_c = "dtn_htfff";




         $conn = mysql_connect($hote_c,$login_c,$pwd_c);
         mysql_select_db($db_c);
#print_r($conn." is connected");
         return($conn);
}


function deconnect(){
mysql_close();
}
















?>
