<?php 
	global $CONFIG;
	
	gatekeeper();
	
	set_context("settings");
	
	$username = get_input("username");
	
	if(!empty($username)){
		$user = get_user_by_username($username);
	} else {
		$user = get_loggedin_user();
	}
	
	if(!empty($user) && $user->canEdit()){
		// set page owner
		set_page_owner($user->getGUID());
		
		if(digest_group_enabled()){
			// get groups user is a member of
			$options = array(
				"type" => "group",
				"relationship" => "member",
				"relationship_guid" => $user->getGUID(),
				"limit" => false
			);
			
			$groups = elgg_get_entities_from_relationship($options);
		}
		
		// build page elements
		$title_text = elgg_echo("digest:usersettings:title");
		$title = elgg_view_title($title_text);
		
		$body = elgg_view("digest/usersettings/form", array("user" => $user, "groups" => $groups));
		
		// build page
		$page_data = $title . $body;
		
		// draw page
		page_draw($title_text, elgg_view_layout("two_column_left_sidebar", "", $page_data));
	} else {
		register_error(elgg_echo("digest:usersettings:error:user"));
		forward($CONFIG->wwwroot . "pg/settings");
	}

?>