<?php

require('reports.php');
$db=db_open("ipv6.sqlite");


$dates=get_dates();


print_r($dates);


function print_overview($dates) {

	print("<table>\n");
	print("<tr><th>Date</th><th>Good</th><th>Trying</th><th>Broken</th></tr>\n");
	print("</table>\n");

}

?>
