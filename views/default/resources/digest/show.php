<?php
/**
 * Show a single digest
 */

use Elgg\BadRequestException;
use Elgg\EntityNotFoundException;

$ts_lower = (int) get_input('ts_lower');
$ts_upper = (int) get_input('ts_upper');
$interval = get_input('interval');

if (empty($ts_lower) || empty($ts_upper) || empty($interval)) {
	throw new BadRequestException(elgg_echo('digest:show:error:input'));
}

// remove some view extensions
digest_prepare_run();

// get group guid
$group_guid = (int) get_input('group_guid');

$vars = array(
	'user' => elgg_get_logged_in_user_entity(),
	'ts_lower' => $ts_lower,
	'ts_upper' => $ts_upper,
	'interval' => $interval
);

// check if we need to display a group
if (!empty($group_guid)) {
	$group = get_entity($group_guid);
	if (!empty($group) && ($group instanceof ElggGroup)) {
		$vars['group'] = $group;
		
		$content = elgg_view('digest/elements/group', $vars);
	}
}

// did we have a valid group, or display site digest
if (!isset($vars['group'])) {
	$content = elgg_view('digest/elements/site', $vars);
}

if (!empty($content)) {
	$params = [
		'title' => elgg_get_site_entity()->name,
		'content' => $content,
		'footer' => elgg_view('digest/elements/footer', $vars),
		'digest_header' => elgg_view('digest/elements/header', $vars),
		'digest_online' => elgg_view('digest/elements/online', $vars),
		'digest_unsubscribe' => elgg_view('digest/elements/unsubscribe', $vars)
	];
	
	echo elgg_view_layout('digest', $params);
} else {
	throw new EntityNotFoundException(elgg_echo('digest:show:no_data'));
}
