<?php 

	global $CONFIG;
	
	$user = get_loggedin_user();

	$ts_lower = sanitise_int($vars["ts_lower"]);
	$ts_upper = sanitise_int($vars["ts_upper"]);
	
	
	// if data returns nothing make sure to echo nothing

	// Everything below this can be customized and extended to your own needs
	
	
	// Friends activity section
	// retrieve recent friends activity
	if($river_items = get_river_items($user->getGUID(), 0, 'friend', '', '', '', 5, 0, $ts_lower, $ts_upper)){
		echo "<h2>" . elgg_echo("content:latest") . "</h2>";
		
		echo "<div id='digest_river_item_list' class='river_item_list'>";
		foreach($river_items as $item){
			if(!empty($item->view)){
				if(get_entity($item->object_guid) && get_entity($item->subject_guid)){
					if(elgg_view_exists($item->view)){
						$body = elgg_view($item->view, array("item" => $item));
						
						echo elgg_view("digest/river/item/wrapper", array("item" => $item, "body" => $body));
					}
				}
			}
		}
		echo "</div>";
		
		echo "<hr />";
	}
	// end Friends activity section
	
	// latest blogs
	if(is_plugin_enabled("blog")){
		$blog_options = array(
			"type" => "object",
			"subtype" => "blog",
			"limit" => 5,
			"created_time_lower" => $ts_lower,
			"created_time_upper" => $ts_upper
		);
		
		if($latest_blogs = elgg_get_entities($blog_options)){
			echo "<h2><a href='" . $vars["url"] . "pg/blog/all/'>" . elgg_echo("blogs") . "</a></h2>";
			
			foreach($latest_blogs as $blog){
				echo "<div>";
				echo "<h3>" . elgg_view("output/url", array("href" => $blog->getURL(), "text" => $blog->title)) . "</h3>";
				echo "<div class='strapline'>";
				echo sprintf(elgg_echo("blog:strapline"), date("F j, Y", $blog->time_created));
				echo " " . elgg_echo('by') . " " . elgg_view("output/url", array("href" => $vars["url"] . "pg/blog/owner/" . $blog->getOwnerEntity()->username, "text" => $blog->getOwnerEntity()->name));
				echo "</div>";
				echo elgg_get_excerpt($blog->description, 200);
				echo " " . elgg_view("output/url", array("href" => $blog->getURL(), "text" => elgg_echo('blog:read_more')));
				echo "</div>";
			}
			
			echo "<hr />";
		}
	}

	// New Groups and users section
	// Groups
	if(is_plugin_enabled("groups")){
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
			echo "<h2><a href='" . $vars["url"]. "pg/groups/world'>" . elgg_echo("groups") . "</a></h2>";
			echo $group_items;
			echo "<hr />";
		}
	}
	
	// users
	if(is_plugin_enabled("profile")){
		$member_items = "";
		
		$member_options = array(
			"type" => "user",
			"limit" => 10,
			"relationship" => "member_of_site",
			"relationship_guid" => $CONFIG->site_guid,
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
			if(is_plugin_enabled("riverdashboard")){
				echo "<h2><a href='" . $vars["url"] . "'>" . elgg_echo("riverdashboard:recentmembers") . "</a></h2>";
			} else {
				echo "<h2><a href='" . $vars["url"] . "'>" . elgg_echo("item:user") . "</a></h2>";
			}
			echo $member_items;
			echo "<hr />";
		}
	}
	// End new groups and users section
	