<?php 
	
	gatekeeper();
	
	$username = get_input("username");
	
	if(!empty($username)){
		$user = get_user_by_username($username);
	} else {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if(!empty($user) && $user->canEdit()){
		// set correct context
		elgg_push_context("settings");
		
		// make breadcrumb
		elgg_push_breadcrumb(elgg_echo("settings"), "settings/user/" . $user->username);
		elgg_push_breadcrumb(elgg_echo("digest:page_menu:settings"));
		
		// set page owner
		elgg_set_page_owner_guid($user->getGUID());
		
		if(digest_group_enabled()){
			// get groups user is a member of
			$options = array(
				"type" => "group",
				"relationship" => "member",
				"relationship_guid" => $user->getGUID(),
				"limit" => false,
				"joins" => array("JOIN " . get_config("dbprefix") . "groups_entity ge ON e.guid = ge.guid"),
				"order_by" => "ge.name ASC"
			);
			
			$groups = elgg_get_entities_from_relationship($options);
		}
		
		// build page elements
		$title_text = elgg_echo("digest:usersettings:title");
		
		$body = elgg_view("digest/usersettings/form", array("user" => $user, "groups" => $groups));
		
		// build page
		$params = array(
			"title" => $title_text,
			"content" => $body
		);
		
		// draw page
		echo elgg_view_page($title_text, elgg_view_layout("one_sidebar", $params));
		
		// reset context
		elgg_pop_context();
	} else {
		register_error(elgg_echo("digest:usersettings:error:user"));
		forward();
	}
	