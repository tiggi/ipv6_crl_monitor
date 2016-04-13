<?php

require('reports.php');
$db=db_open("ipv6.sqlite");

if (isset($_GET['date'])) {
	 $date=$_GET['date'];
} else {
	$date="2016-03-15";
}

if (!validate_date($date)) { 
	die("Invalid date\n");
}

if (isset($_GET['type'])) {
	$type=$_GET['type'];
} else {
	$type="all";
}

print("<html>\n");
get_css();
print("Description: <br> works = IPv6 works fine on at least one CRL distribution point\n");
print("<br>AAAA = At least one CRL distribution point has an AAAA record in DNS, but it is not reachable\n");
print("<br>ipv4 = All CRL distribution points are IPv4 only\n<br>\n");

print_specific($date);




function print_specific($date) {
	$CAs=get_CAs($date);
	//print_r($CAs);

	print("Data from $date");

	print("<table border=\"1\">\n");
	print("<tr><th>CA</th><th>Status</th></tr>\n");
	foreach ($CAs as $idx => $current) {
		print("<tr>\n");
		// CA name
		print("<td>" . $current[0] . "</td>\n");
		// status with colouring 
		print("<td class=\"" . $current[1] . "\">" . $current[1] . "</td>\n");
		print("</tr>\n");
	}
	print("</table>\n");

}


print("<hr>Service run by Ulf Tigerstedt, NDGF-T1<br>\n");
print("</html>\n");


?>

