<?php 

	global $CONFIG;
	
	gatekeeper();
	
	$ts_lower = (int) get_input("ts_lower");
	$ts_upper = (int) get_input("ts_upper");
	$interval = get_input("interval");
	
	if(!empty($ts_lower) && !empty($ts_upper) && !empty($interval)){
		// remove some view extensions
		digest_revert_views();
		
		// get group guid
		$group_guid = (int) get_input("group_guid");
		
		// check if we need to display a group
		if(!empty($group_guid)){
			if($group = get_entity($group_guid)){
				if($group instanceof ElggGroup){
					$title_text = sprintf(elgg_echo("digest:message:title:group"), $CONFIG->site->name, $group->name, elgg_echo("digest:interval:" . $interval));
					
					$data = elgg_view("digest/message/group_body", array("ts_lower" => $ts_lower, "ts_upper" => $ts_upper, "group" => $group));
				}
			}
		}
		
		// no group or invalid group or no data
		if(empty($group_guid) || empty($data)){
			$title_text = sprintf(elgg_echo("digest:message:title:site"), $CONFIG->site->name, elgg_echo("digest:interval:" . $interval));
			
			$data = elgg_view("digest/message/site_body", array("ts_lower" => $ts_lower, "ts_upper" => $ts_upper));
		}
	
		if(!empty($data)){
			echo elgg_view_layout("digest", $title_text, $data);
		} else {
			system_message(elgg_echo("digest:show:no_data"));
			forward();
		}
	} else {
		register_error(elgg_echo("digest:show:error:input"));
		forward();
	}
	