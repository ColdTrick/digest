<?php

	/**
	* Shows the newest members in the Digest
	*
	*/
	global $digest_site_profile_body;
	
	$interval = elgg_extract("user_interval", $vars);
	$site_guid = elgg_get_site_entity()->getGUID();
	
	$key = md5($interval . $site_guid);
	
	if(!isset($digest_site_profile_body)){
		$digest_site_profile_body = array();
	}
	
	if(isset($digest_site_profile_body[$key])){
		// return from memory
		if(!empty($digest_site_profile_body[$key])){
			$title = elgg_view("output/url", array("text" => elgg_echo("members"), "href" => "members" ));
			echo elgg_view_module("digest", $title , $digest_site_profile_body[$key]);
		}		
	} else {
		$ts_lower = (int) elgg_extract("ts_lower", $vars);
		$ts_upper = (int) elgg_extract("ts_upper", $vars);
		
		$member_options = array(
				"type" => "user",
				"limit" => 6,
				"relationship" => "member_of_site",
				"relationship_guid" => elgg_get_site_entity()->getGUID(),
				"inverse_relationship" => true,
				"order_by" => "r.time_created DESC",
				"wheres" => array("(r.time_created BETWEEN " . $ts_lower . " AND " . $ts_upper . ")")
		);
		
		if($newest_members = elgg_get_entities_from_relationship($member_options)){
			$title = elgg_view("output/url", array("text" => elgg_echo("members"), "href" => "members" ));
		
			$content = "<table class='digest-profile'>";
		
			foreach($newest_members as $index => $mem){
				if(($index % 3 == 0)){
					// only 3 per line
					$content .= "<tr>";
				}
		
				$content .= "<td>";
				$content .= elgg_view_entity_icon($mem, 'medium', array('use_hover' => false)) . "<br />";
				$content .= "<a href='" .  $mem->getURL() . "'>" . $mem->name . "</a><br />";
				$content .= $mem->briefdescription;
				$content .= "</td>";
					
				if(($index % 3) === 2 ){
					$content .= "</tr>";
				}
			}
		
			if(($index % 3) !== 2){
				// fill up empty columns
				if(($index + 2) % 3){
					$content .= "<td>&nbsp;</td>";
					$content .= "<td>&nbsp;</td>";
				} elseif(($index + 1) % 3){
					$content .= "<td>&nbsp;</td>";
				}
					
				$content .= "</tr>";
			}
				
			$content .= "</table>";
		
			$digest_site_profile_body[$key] = $content;
			echo elgg_view_module("digest", $title , $content);
		} else {
			$digest_site_profile_body[$key] = false;
		}
	}
		