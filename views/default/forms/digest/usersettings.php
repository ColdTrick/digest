<?php

$user = elgg_extract('user', $vars);

// start making the form
$result = elgg_view('forms/digest/usersettings/site', $vars);
$result .= elgg_view('forms/digest/usersettings/groups', $vars);

if (empty($result)) {
	echo elgg_echo('digest:usersettings:no_settings');
	return;
}

echo $result;

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'user_guid',
	'value' => $user->guid,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
