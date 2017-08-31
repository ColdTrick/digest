<?php
/**
* Shows the newest groups in the Digest
*
*/

$ts_lower = (int) elgg_extract('ts_lower', $vars);
$ts_upper = (int) elgg_extract('ts_upper', $vars);

$newest_groups = elgg_get_entities([
	'type' => 'group',
	'limit' => 6,
	'created_time_lower' => $ts_lower,
	'created_time_upper' => $ts_upper,
]);

if (empty($newest_groups)) {
	return;
}

$title = elgg_view('output/url', [
	'text' => elgg_echo('groups'),
	'href' => 'groups/all',
	'is_trusted' => true,
]);

$group_items = '<table class="digest-groups">';

foreach ($newest_groups as $index => $group) {
	if (($index % 3 == 0)) {
		$group_items .= '<tr>';
	}

	$group_items .= '<td>';
	$group_items .= elgg_view_entity_icon($group, 'medium') . '<br />';
	$group_items .= elgg_view('output/url', [
		'text' => $group->getDisplayName(),
		'href' => $group->getURL(),
		'is_trusted' => true,
	]);
	$group_items .= '</td>';

	if (($index % 3) === 2) {
		$group_items .= '</tr>';
	}
}

if (($index % 3) !== 2) {
	if (($index + 2) % 3) {
		$group_items .= '<td>&nbsp;</td>';
		$group_items .= '<td>&nbsp;</td>';
	} elseif (($index + 1) % 3) {
		$group_items .= '<td>&nbsp;</td>';
	}
	
	$group_items .= '</tr>';
}
	
$group_items .= '</table>';

echo elgg_view_module('digest', $title, $group_items);
