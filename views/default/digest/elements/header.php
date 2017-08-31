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

$group = elgg_extract('group', $vars);
$interval = elgg_extract('interval', $vars);
$site = elgg_get_site_entity();

if (!empty($group)) {
	$title = elgg_echo('digest:message:title:group', [$site->getDisplayName(), $group->getDisplayName(), elgg_echo('digest:interval:' . $interval)]);
} else {
	$title = elgg_echo('digest:message:title:site', [$site->getDisplayName(), elgg_echo('digest:interval:' . $interval)]);
}

echo "<h1>{$title}</h1>";
