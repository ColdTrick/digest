<?php

	/**
	 * This view displays some system information.
	 * 
	 * - Where did this digest come from
	 * - How to adjust your digest settings
	 * - Direct link to unsubscribe
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

	$user = elgg_extract("user", $vars);
	$digest_entity = elgg_extract("group", $vars, elgg_get_site_entity());
	$site = elgg_get_site_entity();
	
	$unsubscribe_link = digest_create_unsubscribe_link($digest_entity->getGUID(), $user);

	$site_url = elgg_view("output/url", array("href" => $site->url, "text" => $site->name));
	$digest_url = elgg_view("output/url", array("href" => $vars["url"] . "digest", "text" => elgg_echo("digest:layout:footer:update")));
	
	echo elgg_echo("digest:layout:footer:info", array($site_url));
	echo "&nbsp;" . $digest_url . "<br />";
	echo elgg_view("output/url", array("href" => $unsubscribe_link, "text" => elgg_echo("digest:layout:footer:unsubscribe")));