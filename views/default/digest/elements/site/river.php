<?php
/**
 * Shows the activity of your friends in the Digest
 *
 */

$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());
$ts_lower = (int) elgg_extract('ts_lower', $vars);
$ts_upper = (int) elgg_extract('ts_upper', $vars);

$river_items = elgg_list_river([
	'relationship' => 'friend',
	'relationship_guid' => $user->getGUID(),
	'limit' => 5,
	'posted_time_lower' => $ts_lower,
	'posted_time_upper' => $ts_upper,
	'pagination' => false,
]);

if (empty($river_items)) {
	return;
}

$title = elgg_view('output/url', [
	'text' => elgg_echo('river:friends'),
	'href' => 'activity/friends/' . $user->username,
	'is_trusted' => true,
]);

echo elgg_view_module('digest', $title, $river_items);
