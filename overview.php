<?php

require('reports.php');
$db=db_open("ipv6.sqlite");

$dates=get_dates();

print("<html>\n");
get_css();

print("IPv6 CRL distribution point IPv6 accessibility status");
print("<br>Good = At least one CRL distribution point has an AAAA record and it can be contacted\n");
print("<br>Trying = At least one CRL distribution point has an AAAA record, but connecting to it over IPv6 does not work\n");
print("<br>Broken = IPv4 only");

print_overview($dates);



function print_overview($dates) {


	print("<table border=\"1\">\n");
	print("<tr><th>Date</th><th>Good</th><th>Trying</th><th>Broken</th></tr>\n");
	foreach ($dates as $idx => $current) {
		$CAs=get_CAs($current['date']);
		$states=count_states($CAs);
		print("<tr>\n");
		print("<td><a href=\"specific.php?date=" . $current['date'] . "\">" . $current['date'] . "</a></td>\n");
		print("<td class=\"works\">" . $states['works'] . "</td>\n");
		print("<td class=\"AAAA\">" . $states['AAAA'] . "</td>\n");
		print("<td class=\"ipv4\">" . $states['ipv4'] . "</td>\n");
		print("</tr>\n");
	}
	print("</table>\n");

}

print("<hr>Service run by Ulf Tigerstedt, NDGF-T1<br>\n");
print("</html>\n");
?>
