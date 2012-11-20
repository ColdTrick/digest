<?php

	/**
	* This view displays the Digest footer.
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

	$group = elgg_extract("group", $vars);
	
	if(!empty($group)){
		$description = $group->briefdescription;
	} else {
		$site = elgg_get_site_entity();
		
		$description = $site->description;
	}
	
	if(!empty($description)){
		echo "<div class='digest-footer-quote'>";
		echo "<table>";
		echo "<tr>";
		echo "<td class='digest-footer-quote-left'>";
		echo "<img src='" . elgg_get_site_url() . "mod/digest/_graphics/quote_left.png' />";
		echo "</td>";
		echo "<td>" . $description . "</td>";
		echo "<td class='digest-footer-quote-right'>";
		echo "<img src='" . elgg_get_site_url() . "mod/digest/_graphics/quote_right.png' />";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
	}