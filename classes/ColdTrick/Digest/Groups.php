<?php

namespace ColdTrick\Digest;

class Groups {
	
	/**
	 * Cleanup digest settings when a user leaves a group
	 *
	 * @param string      $event  the name of the event
	 * @param string      $type   the type of the event
	 * @param \ElggObject $object Object being acted on
	 *
	 * @return void
	 */
	public static function removeDigestSettingOnLeave(\Elgg\Event $event) {
		$object = $event->getObject();
		
		$group = elgg_extract('group', $object);
		$user = elgg_extract('user', $object);
			
		if (empty($user) || empty($group)) {
			return;
		}
		
		$user->removePrivateSetting("digest_{$group->guid}");
	}
}
