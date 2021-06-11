<?php
/**
 * Unsubscribe from the digest
 */

use Elgg\BadRequestException;
use Elgg\EntityPermissionsException;

// get inputs
$guid = (int) get_input('guid');
$user_guid = (int) get_input('user_guid');
$code = get_input('code');

if (empty($guid) || empty($user_guid) || empty($code)) {
	throw new BadRequestException(elgg_echo('digest:unsubscribe:error:input'));
}

$user = get_user($user_guid);
if (empty($user) || !digest_validate_unsubscribe_code($guid, $user, $code)) {
	throw new EntityPermissionsException(elgg_echo('digest:unsubscribe:error:code'));
}

if ($user->setPrivateSetting("digest_{$guid}", DIGEST_INTERVAL_NONE)) {
	system_message(elgg_echo('digest:unsubscribe:success'));
} else {
	register_error(elgg_echo('digest:unsubscribe:error:save'));
}

forward();
