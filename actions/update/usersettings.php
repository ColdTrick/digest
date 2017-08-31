<?php

$user_guid = (int) get_input('user_guid', elgg_get_logged_in_user_guid());
$digests = get_input('digest');

if (empty($user_guid) || empty($digests) || !is_array($digests)) {
	return elgg_error_response(elgg_echo('InvalidParameterException:MissingParameter'));
}

$user = get_user($user_guid);
if (empty($user) || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('InvalidClassException:NotValidElggStar', [$user_guid, 'ElggUser']));
}
	
$error_count = 0;
foreach ($digests as $guid => $interval) {
	if ($interval !== DIGEST_INTERVAL_DEFAULT) {
		if (!($user->setPrivateSetting('digest_' . $guid, $interval))) {
			$error_count++;
		}
	} else {
		$user->removePrivateSetting('digest_' . $guid);
	}
}

if ($error_count > 0) {
	return elgg_error_response(elgg_echo('digest:action:update:usersettings:error:unknown'));
}

return elgg_ok_response('', elgg_echo('digest:action:update:usersettings:success'));
