<?php

	/**
	* Shows the latests blogs in the Digest
	*
	*/
	
	$user = elgg_extract("user", $vars, elgg_get_logged_in_user_entity());
	$ts_lower = (int) elgg_extract("ts_lower", $vars);
	$ts_upper = (int) elgg_extract("ts_upper", $vars);

	$member_options = array(
		"type" => "user",
		"limit" => 10,
		"relationship" => "member_of_site",
		"relationship_guid" => get_config("site_guid"),
		"inverse_relationship" => true,
		"wheres" => array("(r.time_created BETWEEN " . $ts_lower . " AND " . $ts_upper . ")")
	);

	if($newest_members = elgg_get_entities_from_relationship($member_options)){
		$member_items = "<table>";
		
		foreach($newest_members as $index => $mem){
			$mem_url = $mem->getURL();

			if(($index + 1 ) % 2){
				$member_items .= "<tr>";
			}

			$member_items .= "<td><a href='" . $mem_url . "'>" . elgg_view("profile/icon",array('entity' => $mem, 'size' => 'small', 'override' => true)) . "</a> <a href='" . $mem_url . "'>" . $mem->name . "</a></td>";

			if(!(($index + 1 ) % 2)){
				$member_items .= "</tr>";
			}
		}
		
		if((($index + 1 ) % 2)){
			$member_items .= "<td>&nbsp;</td></tr>";
		}
			
		$member_items .= "</table>";
		
		echo elgg_view_module("digest", elgg_echo("members"), $member_items);
	}
	