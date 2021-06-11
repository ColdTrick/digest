<?php
/**
* Shows the newest members in the Digest
*
*/
use Elgg\Database\Clauses\OrderByClause;

global $digest_site_profile_body;

$interval = elgg_extract('interval', $vars);
$site_guid = elgg_get_site_entity()->getGUID();

$key = md5($interval . $site_guid);

if (!isset($digest_site_profile_body)) {
	$digest_site_profile_body = [];
}

if (isset($digest_site_profile_body[$key])) {
	// return from memory
	if (empty($digest_site_profile_body[$key])) {
		return;
	}
	
	$title = elgg_view('output/url', [
		'text' => elgg_echo('members'),
		'href' => 'members',
	]);
	
	echo elgg_view_module('digest', $title , $digest_site_profile_body[$key]);
	return;
}

$ts_lower = (int) elgg_extract('ts_lower', $vars);
$ts_upper = (int) elgg_extract('ts_upper', $vars);

$newest_members = elgg_get_entities([
	'type' => 'user',
	'limit' => 6,
	'relationship' => 'member_of_site',
	'relationship_guid' => elgg_get_site_entity()->guid,
	'inverse_relationship' => true,
	//'order_by' => 'r.time_created DESC',
	'order_by' => new OrderByClause('r.time_created', 'DESC'),
	'created_after' => $ts_lower,
	'created_before' => $ts_upper,
]);

if (empty($newest_members)) {
	$digest_site_profile_body[$key] = false;
	return;
}

$title = elgg_view('output/url', [
	'text' => elgg_echo('members'),
	'href' => 'members',
	'is_trusted' => true,
]);

$content = '<table class="digest-profile">';

foreach ($newest_members as $index => $mem) {
	if (($index % 3 == 0)) {
		// only 3 per line
		$content .= '<tr>';
	}

	$content .= '<td>';
	$content .= elgg_view_entity_icon($mem, 'medium', ['use_hover' => false]) . '<br />';
	$content .= elgg_view('output/url', [
		'text' => $mem->getDisplayName(),
		'href' => $mem->getURL(),
		'is_trusted' => true,
	]);
	$content .= '<br />';
	$content .= $mem->briefdescription;
	$content .= '</td>';
		
	if (($index % 3) === 2) {
		$content .= '</tr>';
	}
}

if (($index % 3) !== 2) {
	// fill up empty columns
	if (($index + 2) % 3) {
		$content .= '<td>&nbsp;</td>';
		$content .= '<td>&nbsp;</td>';
	} elseif (($index + 1) % 3) {
		$content .= '<td>&nbsp;</td>';
	}
		
	$content .= '</tr>';
}
	
$content .= '</table>';

$digest_site_profile_body[$key] = $content;

echo elgg_view_module('digest', $title , $content);
