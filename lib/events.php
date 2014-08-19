<?php
/**
 * All event handlers are bundled here
 */

/**
 * Cleanup digest settings when a user leaves a group
 *
 * @param string $event       'leave'
 * @param string $object_type 'group'
 * @param array  $object      the user and group
 *
 * @return void
 */
function digest_group_leave_event($event, $object_type, $object) {

	if (!is_array($object)) {
		return;
	}
	
	$group = elgg_extract("group", $object);
	$user = elgg_extract("user", $object);
		
	if (empty($user) || empty($group)) {
		return;
	}
	
	$user->removePrivateSetting("digest_" . $group->getGUID());
}
