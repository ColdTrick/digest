<?php

	/**
	* This view displays the Digest header.
	*
	* Available in $vars
	* 	$vars['user'] 		=> the current user for whom we're creating the digest
	* 	$vars['group'] 		=> (optional) the current group for whom we're creating the digest
	* 	$vars['ts_lower']	=> the lower time limit of the content in this digest
	* 	$vars['ts_upper']	=> the upper time limit of the content in this digest
	* 	$vars['interval']	=> the interval of the current digest
	* 							(as defined in DIGEST_INTERVAL_DAILY, DIGEST_INTERVAL_WEEKLY, DIGEST_INTERVAL_FORTNIGHTLY, DIGEST_INTERVAL_MONTHLY)
	*
	*/

	$interval = elgg_extract("interval", $vars);
	$site = elgg_get_site_entity();
	
	echo "<h2>";
	echo $site->name . ": ";
	echo elgg_echo("digest:interval:" . $interval);
	echo "</h2>";