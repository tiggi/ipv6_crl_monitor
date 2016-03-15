<?php

require('reports.php');
$db=db_open("ipv6.sqlite");

$dates=get_dates();


print("IPv6 CRL distribution point IPv6 accessibility status");
print("<br>Trying = At least one CRL distribution point has AAAA record, but it does not work\n");
print("<br>Broken = IPv4 only");

print_overview($dates);



function print_overview($dates) {


	print("<table border=\"1\">\n");
	print("<tr><th>Date</th><th>Good</th><th>Trying</th><th>Broken</th></tr>\n");
	foreach ($dates as $idx => $current) {
		$CAs=get_CAs($current);
		$states=count_states($CAs);
		print("<tr>\n");
		print("<td>" . $current['date'] . "</td>\n");
		print("<td>" . $states['works'] . "</td>\n");
		print("<td>" . $states['AAAA'] . "</td>\n");
		print("<td>" . $states['ipv4'] . "</td>\n");
		print("</tr>\n");
	}
	print("</table>\n");

}

?>
