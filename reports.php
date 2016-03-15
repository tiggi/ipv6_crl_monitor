<?php

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

	return(db_query_get_array("SELECT DISTINCT date FROM ca_ipv6_data ORDER BY 1 DESC"));
}

function get_CAs($date) {
	global $db;
	$CAs=db_query_get_array("SELECT DISTINCT caname FROM ca_ipv6_data");
	$good=db_query_get_array("SELECT DISTINCT caname,status FROM ca_ipv6_data WHERE status=\"works\"");
	$bad=db_query_get_array("SELECT DISTINCT caname,status FROM ca_ipv6_data WHERE status=\"ipv4\"");
	$ugly=db_query_get_array("SELECT DISTINCT caname,status FROM ca_ipv6_data  WHERE status=\"AAAA\"");

	//print_r($good);
	$report=Array();
	foreach($CAs as $a=>$current) {
			$c_good=checkfor($good,$current['caname'],"works");
			$c_bad=checkfor($bad,$current['caname'],"ipv4");
			$c_ugly=checkfor($ugly,$current['caname'],"AAAA");
	
			if ($c_good) {
				$report[]=Array($current['caname'],"works");
			}
			elseif ($c_ugly) {
				$report[]=Array($current['caname'],"AAAA");
			}
			elseif ($c_bad) {
				$report[]=Array($current['caname'],"ipv4");
			}
	}
	return $report;

}

function checkfor($ca_array,$ca,$type) {

	foreach($ca_array as $idx=>$name) {
		//print_r($name);
		if (($name['caname'] == $ca) && ($name['status'] == $type)) return true;
	}
	return false;
}

function count_states($ca_array) {
	$result=Array();
	$result['ipv4']=0;
	$result['AAAA']=0;
	$result['works']=0;
	foreach ($ca_array as $current) {
		$result[$current[1]]++;
	}
	return $result;
}

function validate_date($date) {
	$ret=preg_match("/^([0-9]{4})-([0-9][0-9])-([0-9][0-9])$/",$date);
	if ($ret==1) return true;
	return false;
}

function get_css() {
	print("\n<style>\n");
	print("td.works { background: green; }\n");
	print("td.ipv4 { background: red; }\n");
	print("td.AAAA{ background: yellow; }\n");
	print("</style>\n");
}


?>

