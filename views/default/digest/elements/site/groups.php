<?php

	/**
	* Shows the latests blogs in the Digest
	*
	*/
	
	$user = elgg_extract("user", $vars, elgg_get_logged_in_user_entity());
	$ts_lower = (int) elgg_extract("ts_lower", $vars);
	$ts_upper = (int) elgg_extract("ts_upper", $vars);
	
	$group_options = array(
		"type" => "group",
		"limit" => 10,
		"created_time_lower" => $ts_lower,
		"created_time_upper" => $ts_upper
	);

	if($newest_groups = elgg_get_entities($group_options)){
		
		$group_items = "<table>";
		
		foreach($newest_groups as $index => $group){
			$group_url = $group->getURL();

			if(($index + 1 ) % 2){
				$group_items .= "<tr>";
			}

			$group_items .= "<td><a href='" . $group_url . "'>";
			$group_items .= "<img src='" . $group->getIcon("small") . "' border='0' title='" . $group->$name . "'/>";
			$group_items .= "</a> <a href='" . $group_url . "'>" . $group->name . "</a></td>";

			if(!(($index + 1 ) % 2)){
				$group_items .= "</tr>";
			}
		}
		
		if((($index + 1 ) % 2)){
			$group_items .= "<td>&nbsp;</td></tr>";
		}
			
		$group_items .= "</table>";
		
		echo elgg_view_module("digest", elgg_echo("groups"), $group_items);
	}
	