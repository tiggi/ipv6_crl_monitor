<?

global $db;

function db_open($filename) {
        global $db;

        $db=new SQLite3($filename);

        return($db);
}

function db_query_get_array($query) {
        global $db;

        $statement=$db->prepare($query);
        $result=$statement->execute();
        $ret_arr=Array();
        while ($line=$result->fetchArray()) {
                $ret_arr[]=$line;
        }

        return($ret_arr);
}

function get_dates() {
	global $db;

	return(db_query_get_array("SELECT DISTINCT date FROM ca_ipv6_data ORDER BY 1"));
}



?>

