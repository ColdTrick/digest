<?php 

	$group_guid = (int) get_input("group_guid");
	$digest_interval = get_input("digest_interval");

	$forward_url = REFERER;
	
	if(!empty($group_guid) && !empty($digest_interval)){
		if(($group = get_entity($group_guid)) && elgg_instanceof($group, "group", null, "ElggGroup")){
			if($group->canEdit()){
				$group->digest_interval = $digest_interval;
				
				if($group->save()){
					$forward_url = $group->getURL();
					
					system_message(elgg_echo("digest:action:update:groupsettings:success"));
				} else {
					register_error(elgg_echo("digest:action:update:groupsettings:error:save"));
				}
			} else {
				register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($group_guid)));
			}
		} else {
			register_error(elgg_echo("InvalidClassException:NotValidElggStar", array($group_guid, "ElggGroup")));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}

	forward($forward_url);
