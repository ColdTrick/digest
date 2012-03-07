<?php

	function digest_group_leave_event($event, $object_type, $object){
	
		if(is_array($object)){
			$group = elgg_extract("group", $object);
			$user = elgg_extract("user", $object);
				
			if(!empty($user) && !empty($group)){
				$user->removePrivateSetting("digest_" . $group->getGUID());
			}
		}
	}

	