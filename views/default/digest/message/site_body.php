<?php 

	$user = elgg_get_logged_in_user_entity();

	$ts_lower = sanitise_int($vars["ts_lower"], false);
	$ts_upper = sanitise_int($vars["ts_upper"], false);
	
	
	// if data returns nothing make sure to echo nothing

	// Everything below this can be customized and extended to your own needs
	
	
	// Friends activity section
	// retrieve recent friends activity
	$river_options = array(
		"relationship" => "friend",
		"relationship_guid" => $user->getGUID(),
		"limit" => 5,
		"posted_time_lower" => $ts_lower,
		"posted_time_upper" => $ts_upper
	);
	
	if($river_items = elgg_list_river($river_options)){
		echo "<h2>" . elgg_echo("content:latest") . "</h2>";
		
		echo "<div id='digest_river_item_list' class='river_item_list'>";
		echo $river_items;
		echo "</div>";
		
		echo "<hr />";
	}
	// end Friends activity section
	
	// latest blogs
	if(elgg_is_active_plugin("blog")){
		$blog_options = array(
			"type" => "object",
			"subtype" => "blog",
			"limit" => 5,
			"created_time_lower" => $ts_lower,
			"created_time_upper" => $ts_upper,
			"full_view" => false
		);
		
		if($latest_blogs = elgg_list_entities($blog_options)){
			echo "<h2><a href='" . $vars["url"] . "blog/all'>" . elgg_echo("blog:blogs") . "</a></h2>";
			
			echo $latest_blogs;
			
			echo "<hr />";
		}
	}

	// New Groups and users section
	// Groups
	if(elgg_is_active_plugin("groups")){
		$group_items = "";
		
		$group_options = array(
			"type" => "group",
			"limit" => 10,
			"created_time_lower" => $ts_lower,
			"created_time_upper" => $ts_upper
		);
		
		if($newest_groups = elgg_get_entities($group_options)){
			$group_items .= "<table>";
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
		}
		
		if(!empty($group_items)){
			echo "<h2><a href='" . $vars["url"]. "groups/world'>" . elgg_echo("groups") . "</a></h2>";
			echo $group_items;
			echo "<hr />";
		}
	}
	
	// users
	if(elgg_is_active_plugin("profile")){
		$member_items = "";
		
		$member_options = array(
			"type" => "user",
			"limit" => 10,
			"relationship" => "member_of_site",
			"relationship_guid" => get_config("site_guid"),
			"inverse_relationship" => true,
			"wheres" => array("(r.time_created BETWEEN " . $ts_lower . " AND " . $ts_upper . ")")
		);
		
		if($newest_members = elgg_get_entities_from_relationship($member_options)){
			$member_items .= "<table>";
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
		}
		
		if(!empty($member_items)){
			echo "<h2><a href='" . $vars["url"] . "'>" . elgg_echo("members") . "</a></h2>";
			echo $member_items;
			echo "<hr />";
		}
	}
	// End new groups and users section
	