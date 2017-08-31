<?php

if (!digest_site_enabled()) {
	return;
}

$user = elgg_extract('user', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$site_guid = elgg_get_site_entity()->guid;
$site_interval = $user->getPrivateSetting("digest_{$site_guid}") ?: digest_get_default_site_interval();

$interval_options = [
	DIGEST_INTERVAL_NONE => elgg_echo('digest:interval:none'),
	DIGEST_INTERVAL_DAILY => elgg_echo('digest:interval:daily'),
	DIGEST_INTERVAL_WEEKLY => elgg_echo('digest:interval:weekly'),
	DIGEST_INTERVAL_FORTNIGHTLY => elgg_echo('digest:interval:fortnightly'),
	DIGEST_INTERVAL_MONTHLY => elgg_echo('digest:interval:monthly'),
];

// make form
$body = elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('digest:usersettings:site:setting'),
	'name' => "digest[{$site_guid}]",
	'options_values' => $interval_options,
	'value' => $site_interval,
]);

$title = elgg_echo('digest:usersettings:site:title');
$title .= elgg_view_icon('info', [
	'class' => 'mlm',
	'title' => elgg_echo('digest:usersettings:site:description'),
]);

echo elgg_view_module('info', $title, $body);
