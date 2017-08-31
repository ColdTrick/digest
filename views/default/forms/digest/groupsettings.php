<?php

if (!digest_group_enabled()) {
	return;
}

// check the group
$group = elgg_extract('entity', $vars);

if (!$group instanceof \ElggGroup) {
	return;
}
	
// is this group limited by some other plugin
if (!elgg_trigger_plugin_hook('digest', 'group', ['group' => $group], true)) {
	return;
}

$group_interval = $group->digest_interval ?: digest_get_default_group_interval();

$interval_options = [
	DIGEST_INTERVAL_NONE => elgg_echo('digest:interval:none'),
	DIGEST_INTERVAL_DAILY => elgg_echo('digest:interval:daily'),
	DIGEST_INTERVAL_WEEKLY => elgg_echo('digest:interval:weekly'),
	DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo('digest:interval:fortnightly'),
	DIGEST_INTERVAL_MONTHLY => elgg_echo('digest:interval:monthly'),
];

// make form
echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('digest:groupsettings:setting'),
	'#help' => elgg_echo('digest:groupsettings:description'),
	'name' => 'digest_interval',
	'options_values' => $interval_options,
	'value' => $group_interval,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'group_guid',
	'value' => $group->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
