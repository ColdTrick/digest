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
		"limit" => 6,
		"relationship" => "member_of_site",
		"relationship_guid" => get_config("site_guid"),
		"inverse_relationship" => true,
		"wheres" => array("(r.time_created BETWEEN " . $ts_lower . " AND " . $ts_upper . ")")
	);

	if($newest_members = elgg_get_entities_from_relationship($member_options)){
		$title = elgg_view("output/url", array("text" => elgg_echo("members"), "href" => "members" ));
		
		$member_items = "<table class='digest-profile'>";
		
		foreach($newest_members as $index => $mem){
			if(($index % 3 == 0)){
				$member_items .= "<tr>";
			}

			$member_items .= "<td>";
			$member_items .= elgg_view_entity_icon($mem, 'medium', array('override' => true)) . "<br />";
			$member_items .= "<a href='" .  $mem->getURL() . "'>" . $mem->name . "</a><br />";
			$member_items .= $mem->briefdescription;
			$member_items .= "</td>";
			
			if(($index % 3) === 2 ){
				$member_items .= "</tr>";
			}
		}
		if(($index % 3) !== 2){
			if(($index + 2) % 3){
				$member_items .= "<td>&nbsp;</td>";
				$member_items .= "<td>&nbsp;</td>";
			} elseif(($index + 1) % 3){
				$member_items .= "<td>&nbsp;</td>";
			}
			
			$member_items .= "</tr>";
		}
			
		$member_items .= "</table>";
		
		echo elgg_view_module("digest", $title , $member_items);
	}
	