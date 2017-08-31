<?php

$group_guid = (int) get_input('group_guid');
$digest_interval = get_input('digest_interval');

if (empty($group_guid) || empty($digest_interval)) {
	return elgg_error_response(elgg_echo('InvalidParameterException:MissingParameter'));
}

$group = get_entity($group_guid);
if (!$group instanceof \ElggGroup) {
	return elgg_error_response(elgg_echo('InvalidClassException:NotValidElggStar', [$group_guid, 'ElggGroup']));
}

if (!$group->canEdit()) {
	return elgg_error_response(elgg_echo('InvalidParameterException:GUIDNotFound', [$group_guid]));
}

$group->digest_interval = $digest_interval;

if (!$group->save()) {
	return elgg_error_response(elgg_echo('digest:action:update:groupsettings:error:save'));
}

return elgg_ok_response('', elgg_echo('digest:action:update:groupsettings:success'), $group->getURL());
