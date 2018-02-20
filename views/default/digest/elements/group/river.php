<?php
/**
* Shows the activity of the current group in the Digest
*/

$user = elgg_extract('user', $vars, elgg_get_logged_in_user_entity());
$group = elgg_extract('group', $vars);
$ts_lower = (int) elgg_extract('ts_lower', $vars);
$ts_upper = (int) elgg_extract('ts_upper', $vars);

$dbprefix = get_config('dbprefix');
$group_guid = $group->getGUID();

$offset = 0;
$limit = 25;

// set river options
$options = [
        'pagination' => false,
	'limit' => $limit,
	'offset' => $offset,
	'distinct' => false,
	'joins' => [
		"JOIN {$dbprefix}entities e1 ON e1.guid = rv.object_guid",
		"LEFT JOIN {$dbprefix}entities e2 ON e2.guid = rv.target_guid",
	],
	'wheres' => [
		"(e1.container_guid = {$group_guid} OR e1.guid = {$group_guid} OR e2.container_guid = {$group_guid})",
                "rv.posted BETWEEN " . $ts_lower . " AND " . $ts_upper
	],
];

$items = elgg_get_river($options);

if (empty($items)) {
	return;
}

$title = elgg_view('output/url', [
	'text' => elgg_echo('groups:activity'),
	'href' => $group->getURL(),
	'is_trusted' => true,
]);

$content = elgg_view('page/components/list', [
	'list_class' => 'elgg-list-river elgg-river',
	'items' => $items,
	'pagination' => false,
]);

echo elgg_view_module('digest', $title, $content);

// probably done to force cleanup of memory
unset($items);
