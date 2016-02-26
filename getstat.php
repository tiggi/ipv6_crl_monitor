<?php
date_default_timezone_set("Europe/Helsinki");


$files=glob("/etc/grid-security/certificates/*.crl_url");

global $db;

$db=new SQLite3("ipv6.sqlite");


foreach ($files as $a) {

	$temp = explode('/',$a);
	//print_r($temp);
	// $temp[4] filename
	$ca_base = strtok($temp[4],'.');
	//print_r($ca);
	$ret_tuple=check_aaaa(crl_hostnames($a));

	foreach ($ret_tuple as $current) {
//		print_r($current);
		insert_database($ca_base,$current[0],$current[1]);
	}

	#print_r($ret_tuple);
	

} 

function crl_hostnames($crl_url) {
	$urls=file($crl_url);
	//print_r($urls);
	foreach ($urls as $now) {
		$ret=preg_match("/http:\/\/([a-z0-9.-]{2,})\/.*/",$now,$arr);
		//checkdnsrr($host,"AAAA");
		//print_r($arr);
		if ($ret == 1) {
			$hostnames[]=$arr[1];
		}
	}
	return $hostnames;
}

function check_aaaa($hostnames) {
	$retarr=Array();
	foreach ($hostnames as $host) {

		$dns_arr=dns_get_record($host,DNS_AAAA);
		if (count($dns_arr)>=1) {
		//	print($host . " has AAAA\n");
		//	print_r($dns_arr);
			foreach ($dns_arr as $multiple) {
				$ret=check_connectivity($multiple["ipv6"]);
				if ($ret) {
					$retarr[]=Array($host,"works");
				} else {
					$retarr[]=Array($host,"AAAA");
				}
			}
		} else {
		//	print($host . " is IPv4 only\n");
				$retarr[]=Array($host,"ipv4");
		}
	}
	return $retarr;

}

function check_connectivity($ipv6) {
	$fp = fsockopen("[" . $ipv6 . "]",80,$errno,$srrstr,10);
	
	if (!$fp) {
		return false;
	} else {
		fclose($fp);
		return true;
	}

	return;
}

function insert_database($caname,$hostname,$status) {
	global $db;

	$today = date('Y-m-d');
	print("$caname - $hostname - $status\n");
	$statement=$db->prepare("INSERT INTO ca_ipv6_data VALUES(:date,:caname,:hostname,:status)");
	$statement->bindValue(':date',$today,SQLITE3_TEXT);
	$statement->bindValue(':caname',$caname,SQLITE3_TEXT);
	$statement->bindValue(':hostname',$hostname,SQLITE3_TEXT);
	$statement->bindValue(':status',$status,SQLITE3_TEXT);
	$statement->execute();
}
			


?>
