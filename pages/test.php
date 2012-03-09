<?php 
	
	admin_gatekeeper();

	// remove some view extensions
	digest_revert_views();
	
	// set some interval settings
	$ts_upper = time();
	$ts_lower = time() - (60*60*24*31);
	$interval = "monthly";
	
	$user = elgg_get_logged_in_user_entity();
	
	$vars = array(
		"user" => $user,
		"ts_lower" => $ts_lower,
		"ts_upper" => $ts_upper,
		"interval" => $interval
	);
	
	$group_options = array(
		"type" => "group",
		"limit" => 1,
		"relationship" => "member",
		"relationship_guid" => $user->getGUID()
	);
	
	if($groups = elgg_get_entities($group_options)){
		$vars["group"] = $groups[0];
		
		$content = elgg_view("digest/elements/group", $vars);
	}
	
// 	$content = elgg_view("digest/elements/site", $vars);
	
	$params = array(
		"title" => elgg_get_site_entity()->name,
		"content" => $content,
		"footer" => elgg_view("digest/elements/footer", $vars),
		"digest_header" => elgg_view("digest/elements/header", $vars),
		"digest_online" => elgg_view("digest/elements/online", $vars),
		"digest_unsubscribe" => elgg_view("digest/elements/unsubscribe", $vars)
	);
	
	$msgbody = elgg_view_layout("digest", $params);

	echo $msgbody;
	
	// mail the result?
	if(get_input("mail")){
		if(digest_send_mail($user, $subject, $msgbody, $digest_url, true)){
			echo "mail";
		}
	}
	