<?php 

	gatekeeper();
	
	$group_guid = get_input("group_guid");
	$digest_interval = get_input("digest_interval");

	$forward_url = $_SERVER["HTTP_REFERER"];
	
	if(!empty($group_guid) && !empty($digest_interval)){
		if($group = get_entity($group_guid)){
			if($group->canEdit()){
				$group->digest_interval = $digest_interval;
				
				if($group->save()){
					$forward_url = $group->getURL();
					
					system_message(elgg_echo("digest:action:update:groupsettings:success"));
				} else {
					register_error(elgg_echo("digest:action:update:groupsettings:error:save"));
				}
			} else {
				register_error(elgg_echo("digest:action:update:groupsettings:error:can_edit"));
			}
		} else {
			register_error(elgg_echo("digest:action:update:groupsettings:error:entity"));
		}
	} else {
		register_error(elgg_echo("digest:action:update:groupsettings:error:input"));
	}

	forward($forward_url);

?>