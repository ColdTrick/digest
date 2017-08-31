<?php

$day_strings = [
	elgg_echo('digest:day:sunday'),
	elgg_echo('digest:day:monday'),
	elgg_echo('digest:day:tuesday'),
	elgg_echo('digest:day:wednesday'),
	elgg_echo('digest:day:thursday'),
	elgg_echo('digest:day:friday'),
	elgg_echo('digest:day:saturday')
];

if (!digest_site_enabled()) {
	echo elgg_echo('digest:admin:stats:site:not_enabled');
	return;
}
	
$site_stats = elgg_get_plugin_setting('site_statistics', 'digest');
if (empty($site_stats)) {
	echo elgg_echo('digest:admin:stats:not_collected');
	return;
}

$site_stats = digest_compress_statistics(json_decode($site_stats, true));

$general = elgg_extract('general', $site_stats);
if (empty(elgg_extract('ts_start_cron', $general))) {
	echo elgg_echo('digest:admin:stats:not_collected');
	return;
}
		
// site digest has run at least once
$gen_table = "<table class='elgg-table mbl'>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:server_name") . "</td>";
$gen_table .= "<td>" . elgg_extract("server_name", $general, "&nbsp;") . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:settings:interval:site_default") . "</td>";
$gen_table .= "<td>" . elgg_echo("digest:interval:" . digest_get_default_site_interval()) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:ts_start_cron") . "</td>";
$gen_table .= "<td>" . elgg_view_friendly_time(elgg_extract("ts_start_cron", $general, 0)) . " (" . htmlspecialchars(date(elgg_echo("friendlytime:date_format"), elgg_extract("ts_start_cron", $general, 0))) . ") </td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_start_digest") . "</td>";
$gen_table .= "<td>" . digest_readable_time(elgg_extract("mts_start_digest", $general, 0) - elgg_extract("ts_start_cron", $general, 0)) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:site:general:mts_user_selection_done") . "</td>";
$gen_table .= "<td>" . digest_readable_time(elgg_extract("mts_user_selection_done", $general, 0) - elgg_extract("mts_start_digest", $general, 0)) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:mts_end_digest") . "</td>";
$gen_table .= "<td>" . digest_readable_time(elgg_extract("mts_end_digest", $general, 0) - elgg_extract("mts_start_digest", $general, 0)) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
$gen_table .= "<td>" . (int) elgg_extract("users", $general, 0) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
$gen_table .= "<td>" . (int) elgg_extract("mails", $general, 0) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_start") . "</td>";
$gen_table .= "<td>" . digest_readable_bytes(elgg_extract("peak_memory_start", $general, 0)) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "<tr>";
$gen_table .= "<td>" . elgg_echo("digest:admin:stats:general:peak_memory_end") . "</td>";
$gen_table .= "<td>" . digest_readable_bytes(elgg_extract("peak_memory_end", $general, 0)) . "</td>";
$gen_table .= "</tr>";

$gen_table .= "</table>";

// display interval stats
$interval_table = "<table class='elgg-table mbm'>";

$interval_table .= "<tr>";
$interval_table .= "<th>&nbsp;</th>";
$interval_table .= "<th>" . elgg_echo("digest:interval:daily") . "</th>";
$interval_table .= "<th>" . elgg_echo("digest:interval:weekly") . "</th>";
$interval_table .= "<th>" . elgg_echo("digest:interval:fortnightly") . "</th>";
$interval_table .= "<th>" . elgg_echo("digest:interval:monthly") . "</th>";
$interval_table .= "</tr>";

// distribution settings
$interval_table .= "<tr>";
$interval_table .= "<td>" . elgg_echo("digest:distribution") . "</td>";
$interval_table .= "<td>&nbsp;</td>";

$distribution = digest_get_default_distribution(DIGEST_INTERVAL_WEEKLY);
if ($distribution === "distributed") {
	$dis = elgg_echo("digest:distribution:distributed");
} else {
	$dis = $day_strings[$distribution];
}
$interval_table .= "<td>" . $dis . "</td>";

$distribution = digest_get_default_distribution(DIGEST_INTERVAL_FORTNIGHTLY);
if ($distribution === "distributed") {
	$dis = elgg_echo("digest:distribution:distributed");
} else {
	$dis = elgg_echo("digest:admin:stats:distribution:fortnightly", array($day_strings[$distribution]));
}
$interval_table .= "<td>" . $dis . "</td>";

$distribution = digest_get_default_distribution(DIGEST_INTERVAL_MONTHLY);
if ($distribution === "distributed") {
	$dis = elgg_echo("digest:distribution:distributed");
} else {
	$dis = elgg_echo("digest:admin:stats:distribution:monthly", array($distribution));
}
$interval_table .= "<td>" . $dis . "</td>";
$interval_table .= "</tr>";

// users processed
$interval_table .= "<tr>";
$interval_table .= "<td>" . elgg_echo("digest:admin:stats:users") . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_DAILY]["users"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_WEEKLY]["users"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["users"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_MONTHLY]["users"] . "</td>";
$interval_table .= "</tr>";

// mails sent
$interval_table .= "<tr>";
$interval_table .= "<td>" . elgg_echo("digest:admin:stats:mails") . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_DAILY]["mails"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_WEEKLY]["mails"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["mails"] . "</td>";
$interval_table .= "<td>" . $site_stats[DIGEST_INTERVAL_MONTHLY]["mails"] . "</td>";
$interval_table .= "</tr>";

// processing time
$interval_table .= "<tr>";
$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_time") . "</td>";
$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_DAILY]["total_time"]) . "</td>";
$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_WEEKLY]["total_time"]) . "</td>";
$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_time"]) . "</td>";
$interval_table .= "<td>" . digest_readable_time($site_stats[DIGEST_INTERVAL_MONTHLY]["total_time"]) . "</td>";
$interval_table .= "</tr>";

// memory used
$interval_table .= "<tr>";
$interval_table .= "<td>" . elgg_echo("digest:admin:stats:total_memory") . "</td>";
$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_DAILY]["total_memory"]) . "</td>";
$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_WEEKLY]["total_memory"]) . "</td>";
$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_FORTNIGHTLY]["total_memory"]) . "</td>";
$interval_table .= "<td>" . digest_readable_bytes($site_stats[DIGEST_INTERVAL_MONTHLY]["total_memory"]) . "</td>";
$interval_table .= "</tr>";

$interval_table .= "</table>";

echo $gen_table . $interval_table;
